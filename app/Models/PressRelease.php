<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PressRelease extends Model
{
    protected $fillable = [
        'original_link',
        'release_date'
    ];

    protected static function boot()
    {
        parent::boot();

        // Retrieve article when model is retrieved
        static::retrieved(function ($model) {
            $model->loadRelease();
        });

        // Delete article when model is deleted
        static::deleted(function ($model) {
            $model->deleteRelease();
        });
    }

    public function loadRelease()
    {
        $filePath = "press-releases/{$this->release_date}.html";

        if (!Storage::disk('local')->exists($filePath)) {
            logger()->error("Press Release Not Found: {$filePath}");
            $this->attributes['contents'] = null; // Set to null instead of returning array
            return;
        }

        $this->attributes['contents'] = Storage::disk('local')->get($filePath);
    }
}
