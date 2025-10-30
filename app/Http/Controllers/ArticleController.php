<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        return  ['status' => 'ok', 'data' => Article::with('category')
            ->orderBy('date', 'DESC')
            ->get()
            ->map(function ($article) {
                return [
                    'id'             => $article->id,
                    '_id'            => $article->_id,
                    'title'          => $article->title, // Uses accessor
                    'byline'         => $article->byline,
                    'date'           => $article->formatted_date, // Uses accessor
                    'category_id'    => $article->article_category_id,
                    'category_label' => $article->category_label, // Uses accessor
                    'category_order' => $article->category_order, // Uses accessor
                ];
            })];
    }

    public function show(Request $request, string $slug)
    {
        $article = Article::where('_id', $slug)
            ->with('category')
            ->first();

        if (!$article) {
            return ['status' => 'error', 'message' => 'Invalid Article Slug'];
        }

        return ['status' => 'ok', 'data' => $article];
    }
}
