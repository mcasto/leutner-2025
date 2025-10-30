<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lecture extends Model
{
    private $idx = 1;

    protected $fillable = [
        '_id',
        'title',
        'video'
    ];

    protected static function boot()
    {
        parent::boot();

        // Retrieve photos
        static::retrieved(function ($model) {
            $model->loadPhotos();
        });
    }

    public function loadPhotos()
    {
        $photoPath = "photos/{$this->_id}";

        $this->attributes['meta'] = Storage::disk('public')
            ->get("{$photoPath}/.meta.json");

        $photos = collect(Storage::disk('public')
            ->files($photoPath))
            ->filter(function ($filename) {
                $filename = basename($filename);
                return substr($filename, 0, 1) != '.';
            })
            ->map(function ($filename) {
                return [
                    'value' => uniqid(),
                    'label' => $this->idx++,
                    'src' => "/storage/{$filename}"
                ];
            })
            ->values();

        $this->attributes['photos'] = $photos;

        $this->attributes['date'] = $this->date;
    }
}
