<?php

namespace App\Console\Commands;

use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\HTMLToMarkdown\HtmlConverter;

class ImportMediumArticle extends Command
{
    protected $signature = 'articles:import-medium {file : Path to the Medium HTML file}';

    protected $description = 'Import a Medium article from an HTML export into the database and articles directory';

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

        $ogTitle = $this->getMetaContent($xpath, 'og:title');
        $ogUrl = $this->getMetaContent($xpath, 'og:url');
        $publishedTime = $this->getMetaContent($xpath, 'article:published_time');
        $date = $publishedTime ? date('Y-m-d', strtotime($publishedTime)) : date('Y-m-d');

        // Extract paragraphs from first <article>, starting after a <p> whose text is "Share"
        $paragraphs = $xpath->query('//article[1]//p');
        $capturing = false;
        $paras = [];
        foreach ($paragraphs as $p) {
            if ($capturing) {
                $paras[] = $dom->saveHTML($p);
            }
            if (trim($p->textContent) === 'Share') {
                $capturing = true;
            }
        }

        if (empty($paras)) {
            $this->warn('No paragraphs found after "Share" marker — capturing all paragraphs in article instead.');
            foreach ($paragraphs as $p) {
                $paras[] = $dom->saveHTML($p);
            }
        }

        if (empty($paras)) {
            $this->error('No article paragraphs found in the HTML file.');
            return 1;
        }

        $this->info('Extracted from HTML:');
        $this->line("  Title:  " . ($ogTitle ?? '(not found)'));
        $this->line("  URL:    " . ($ogUrl ?? '(not found)'));
        $this->line("  Date:   {$date}");
        $this->newLine();

        $suggestedId = $this->slugify($ogTitle ?? basename($filePath, '.html'));

        $id     = $this->ask('Article ID (slug used as filename)', $suggestedId);
        $label  = $this->ask('Article title/label', $ogTitle ?? '');
        $byline = $this->ask('Byline', 'Carol E. Leutner');
        $url    = $this->ask('Original URL', $ogUrl ?? '');
        $date   = $this->ask('Date (YYYY-MM-DD)', $date);

        $categoryId = $this->selectCategory();
        if ($categoryId === null) {
            return 1;
        }

        $exists = DB::table('articles')->where('_id', $id)->first();
        if ($exists) {
            $this->error("An article with _id '{$id}' already exists in the database.");
            return 1;
        }

        $converter = new HtmlConverter(['strip_tags' => false]);
        $markdown  = $converter->convert(implode('', $paras));

        Storage::disk('local')->put("articles/{$id}.md", $markdown);

        $sortOrder = (DB::table('articles')->max('sort_order') ?? 0) + 10;

        DB::table('articles')->insert([
            '_id'                 => $id,
            'folder'              => 'Medium',
            'label'               => $label,
            'byline'              => $byline,
            'date'                => $date,
            'url'                 => $url,
            'sort_order'          => $sortOrder,
            'thumbnail'           => '',
            'article_category_id' => $categoryId,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $this->newLine();
        $this->info("Article imported successfully.");
        $this->line("  DB record:  articles._id = '{$id}'");
        $this->line("  Markdown:   storage/app/private/articles/{$id}.md");

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

    private function getMetaContent(DOMXPath $xpath, string $property): ?string
    {
        foreach (["@property='{$property}'", "@name='{$property}'"] as $attr) {
            $nodes = $xpath->query("//meta[{$attr}]/@content");
            if ($nodes && $nodes->length > 0) {
                return trim($nodes->item(0)->textContent);
            }
        }
        return null;
    }

    private function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', trim($text));
        return trim($text, '-');
    }
}
