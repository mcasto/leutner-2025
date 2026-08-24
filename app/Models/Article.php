<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $fillable = [
        '_id',
        'folder',
        'label',
        'byline',
        'date',
        'url',
        'sort_order',
        'thumbnail',
        'article_id'
    ];

    protected $appends = [
        'urlLabel',
        'title',
        'formatted_date',
        'category_label',
        'category_order'
    ];

    protected static function boot()
    {
        parent::boot();

        // Retrieve article when model is retrieved
        static::retrieved(function ($model) {
            $model->loadArticleFile();
        });

        // Delete article when model is deleted
        static::deleted(function ($model) {
            $model->deleteArticleFile();
        });
    }

    public function loadArticleFile()
    {
        $filePath = "articles/{$this->_id}.md";

        if (!Storage::disk('local')->exists($filePath)) {
            $this->attributes['contents'] = null; // Set to null instead of returning array

            // Some articles have no body of their own - their "content" is a
            // downloadable file (e.g. a PDF) rather than a markdown article,
            // so there's nothing missing to warn about in that case.
            if (!$this->linksToDownloadableFile()) {
                logger()->error("Article Not Found: {$filePath}");
            }

            return;
        }

        $this->attributes['contents'] = Storage::disk('local')->get($filePath);
    }

    protected function linksToDownloadableFile(): bool
    {
        return (bool) preg_match('/\.pdf$/i', (string) $this->url);
    }

    public function deleteArticleFile()
    {
        $filePath = "articles/{$this->_id}.md";
        if (Storage::disk('local')
            ->exists($filePath)
        ) {
            Storage::disk('local')->delete($filePath);
        }
    }

    public function getUrlLabelAttribute()
    {
        return $this->folder != 'Website' ? 'View Original at ' . $this->folder : '';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function getTitleAttribute()
    {
        return $this->label;
    }

    public function getFormattedDateAttribute()
    {
        return date("M j, Y", strtotime($this->date));
    }

    public function getCategoryLabelAttribute()
    {
        return $this->category->label ?? null;
    }

    public function getCategoryOrderAttribute()
    {
        return $this->category->sort_order ?? null;
    }
}
