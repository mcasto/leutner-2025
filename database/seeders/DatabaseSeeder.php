<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Lecture;
use App\Models\MailchimpResponse;
use App\Models\Navigation;
use App\Models\Photo;
use App\Models\PressRelease;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $dictionary = [
            'article_categories' => ArticleCategory::class,
            'articles' => Article::class,
            'contacts' => Contact::class,
            'galleries' => Gallery::class,
            'lectures' => Lecture::class,
            'mailchimp_responses' => MailchimpResponse::class,
            'navigation' => Navigation::class,
            'photos' => Photo::class,
            'press_releases' => PressRelease::class,
            'videos' => Video::class,
            'users' => User::class
        ];

        $dataFiles = glob(__DIR__ . '/data/*.csv');
        foreach ($dataFiles as $file) {
            preg_match("/u466389499_leutner---2025-10-29--[^_]+_([^\.]+)/", $file, $m);
            $tableName = $m[1];
            $model = new $dictionary[$tableName];
            $fp = fopen($file, 'r');
            $headers = [];
            $data = [];
            while ($line = fgetcsv($fp)) {
                if (count($headers) == 0) {
                    $headers = $line;
                    continue;
                }

                $data[] = array_combine($headers, $line);
            }

            $data = collect($data);

            $data = $data->map(function ($row) use ($tableName) {
                unset($row['id']);
                if (isset($row['date']) && !in_array($tableName, ['articles', 'lectures'])) {
                    $row['created_at'] = $row['date'];
                    unset($row['date']);
                }
                if (isset($row['category'])) {
                    $row['article_category_id'] = $row['category'];
                    unset($row['category']);
                }
                if ($tableName == 'press_releases') {
                    unset($row['contents']);
                }
                return $row;
            });

            foreach ($data->toArray() as $rec) {
                $model->create($rec);
            }
        }
    }
}
