<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\HTMLToMarkdown\HtmlConverter;

class ArticleImportController extends Controller
{
    public function setup(Request $request): JsonResponse
    {
        if (!$this->authorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'categories' => DB::table('article_categories')->orderBy('sort_order')->get(),
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        if (!$this->authorized($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'type'        => 'required|in:medium,chl',
            'file'        => 'required|file',
            'id'          => ['required', 'string', 'regex:/^[a-z0-9][a-z0-9-]*[a-z0-9]$/'],
            'label'       => 'required|string|max:255',
            'byline'      => 'required|string|max:255',
            'url'         => 'required|string|max:500',
            'date'        => 'required|date_format:Y-m-d',
            'category_id' => 'required|integer|exists:article_categories,id',
        ]);

        if (DB::table('articles')->where('_id', $validated['id'])->exists()) {
            return response()->json(['error' => "An article with ID '{$validated['id']}' already exists."], 422);
        }

        $html = file_get_contents($request->file('file')->getRealPath());
        $dom  = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR);
        $xpath = new DOMXPath($dom);

        $markdown = $validated['type'] === 'medium'
            ? $this->parseMedium($dom, $xpath)
            : $this->parseChl($request, $dom, $xpath, $validated['id']);

        if ($markdown === null) {
            return response()->json(['error' => 'No article content found in the HTML file.'], 422);
        }

        Storage::disk('local')->put("articles/{$validated['id']}.md", $markdown);

        $sortOrder = (DB::table('articles')->max('sort_order') ?? 0) + 10;

        DB::table('articles')->insert([
            '_id'                 => $validated['id'],
            'folder'              => $validated['type'] === 'medium' ? 'Medium' : 'Cuenca High Life',
            'label'               => $validated['label'],
            'byline'              => $validated['byline'],
            'date'                => $validated['date'],
            'url'                 => $validated['url'],
            'sort_order'          => $sortOrder,
            'article_category_id' => $validated['category_id'],
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        Cache::forget('leutner-articles-indes');

        return response()->json(['message' => "Article '{$validated['id']}' imported successfully."]);
    }

    private function parseMedium(DOMDocument $dom, DOMXPath $xpath): ?string
    {
        $paragraphs = $xpath->query('//article[1]//p');
        $capturing  = false;
        $paras      = [];

        foreach ($paragraphs as $p) {
            if ($capturing) {
                $paras[] = $dom->saveHTML($p);
            }
            if (trim($p->textContent) === 'Share') {
                $capturing = true;
            }
        }

        // Fallback: capture all article paragraphs if "Share" marker not found
        if (empty($paras)) {
            foreach ($paragraphs as $p) {
                $paras[] = $dom->saveHTML($p);
            }
        }

        return empty($paras) ? null : (new HtmlConverter(['strip_tags' => false]))->convert(implode('', $paras));
    }

    private function parseChl(Request $request, DOMDocument $dom, DOMXPath $xpath, string $id): ?string
    {
        if ($request->hasFile('images')) {
            $imagePath = storage_path("app/public/article-images/{$id}");
            if (!is_dir($imagePath)) {
                mkdir($imagePath, 0755, true);
            }
            foreach ($request->file('images') as $image) {
                $image->move($imagePath, $image->getClientOriginalName());
            }
        }

        // Remove img elements (images are uploaded separately or stripped)
        $imgs = $xpath->query('//img');
        foreach ($imgs as $img) {
            $img->parentNode->removeChild($img);
        }

        $paras = [];
        foreach ($xpath->query('//p') as $p) {
            $paras[] = $dom->saveHTML($p);
        }

        return empty($paras) ? null : (new HtmlConverter(['strip_tags' => false]))->convert(implode('', $paras));
    }

    private function authorized(Request $request): bool
    {
        return $request->header('X-Admin-Email') === env('ARTICLE_ADMIN_EMAIL')
            && $request->header('X-Admin-Password') === env('ARTICLE_ADMIN_PASSWORD');
    }
}
