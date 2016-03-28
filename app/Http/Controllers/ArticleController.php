<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function getArticles(Request $request)
    {
        $q = new Article;
        if (null !== $request->input('desc')) {
            $q = $q->where('description', 'like', '%' . $request->input('desc') . '%');
        }
        if (null !== $request->input('title')) {
            $q = $q->where('title', 'like', '%' . $request->input('title') . '%');
        }
        $articles = $q->paginate(1);
        return response()->json($articles);
    }

    public function getArticle($id)
    {
        $article = Article::findorFail($id);
        return response()->json($article);
    }

    public function saveArticle(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json($article);
    }

    public function deleteArticle($id)
    {
        $article = Article::find($id);
        $article->delete();
        return response()->json([
            'success' => true,
        ]);
    }
    public function updateArticle(Request $request, $id)
    {
        $article = Article::find($id);
        $article->title = $request->input('title');
        $article->description = $request->input('description');
        $article->save();
        return response()->json($article);
    }

}
