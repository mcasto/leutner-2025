<?php

namespace App\Console\Commands;

use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\HTMLToMarkdown\HtmlConverter;

class ImportChlArticle extends Command
{
    protected $signature = 'articles:import-chl {file : Path to the Cuenca High Life HTML file}';

    protected $description = 'Import a Cuenca High Life article from an HTML file into the database and articles directory';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $html = file_get_contents($filePath);
        $dom = new DOMDocument();
        @$dom->loadHTML($html, LIBXML_NOERROR);
        $xpath = new DOMXPath($dom);

        // The _files dir sits alongside the HTML file with the same base name
        $filesDir = preg_replace('/\.html?$/i', '_files', $filePath);

        $id     = $this->ask('Article ID (slug used as filename)');
        $label  = $this->ask('Article title/label');
        $byline = $this->ask('Byline', 'Carol E. Leutner');
        $url    = $this->ask('Original CHL URL');
        $date   = $this->ask('Date (YYYY-MM-DD)', date('Y-m-d'));

        $categoryId = $this->selectCategory();
        if ($categoryId === null) {
            return 1;
        }

        $exists = DB::table('articles')->where('_id', $id)->first();
        if ($exists) {
            $this->error("An article with _id '{$id}' already exists in the database.");
            return 1;
        }

        // Copy images from the _files directory and remove <img> elements
        $imagePath = storage_path("app/public/article-images/{$id}");
        $imagesCopied = 0;

        if (is_dir($filesDir)) {
            if (!is_dir($imagePath)) {
                mkdir($imagePath, 0755, true);
            }

            $imgs = $xpath->query('//img');
            foreach ($imgs as $img) {
                $src = $img->getAttribute('src');
                if (!$src) {
                    $img->parentNode->removeChild($img);
                    continue;
                }

                $srcFile = dirname($filePath) . DIRECTORY_SEPARATOR . $src;
                if (file_exists($srcFile)) {
                    copy($srcFile, $imagePath . DIRECTORY_SEPARATOR . basename($srcFile));
                    $imagesCopied++;
                }

                $img->parentNode->removeChild($img);
            }
        } else {
            $this->warn("No _files directory found at: {$filesDir}");
            $this->warn("Images will not be copied.");

            $imgs = $xpath->query('//img');
            foreach ($imgs as $img) {
                $img->parentNode->removeChild($img);
            }
        }

        // Extract all <p> elements
        $paras = [];
        foreach ($xpath->query('//p') as $p) {
            $paras[] = $dom->saveHTML($p);
        }

        if (empty($paras)) {
            $this->error('No paragraphs found in the HTML file.');
            return 1;
        }

        $converter = new HtmlConverter(['strip_tags' => false]);
        $markdown  = $converter->convert(implode('', $paras));

        Storage::disk('local')->put("articles/{$id}.md", $markdown);

        $sortOrder = (DB::table('articles')->max('sort_order') ?? 0) + 10;

        DB::table('articles')->insert([
            '_id'                 => $id,
            'folder'              => 'Cuenca High Life',
            'label'               => $label,
            'byline'              => $byline,
            'date'                => $date,
            'url'                 => $url,
            'sort_order'          => $sortOrder,
            'article_category_id' => $categoryId,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $this->newLine();
        $this->info("Article imported successfully.");
        $this->line("  DB record:  articles._id = '{$id}'");
        $this->line("  Markdown:   storage/app/private/articles/{$id}.md");
        if ($imagesCopied > 0) {
            $this->line("  Images:     {$imagesCopied} copied to storage/app/public/article-images/{$id}/");
        }

        return 0;
    }

    private function selectCategory(): ?int
    {
        $categories = DB::table('article_categories')->orderBy('sort_order')->get();

        $this->newLine();
        $this->table(['ID', 'Category'], $categories->map(fn($c) => [$c->id, $c->label])->toArray());

        $categoryId = (int) $this->ask('Category ID');

        $valid = $categories->firstWhere('id', $categoryId);
        if (!$valid) {
            $this->error("Invalid category ID: {$categoryId}");
            return null;
        }

        return $categoryId;
    }
}
