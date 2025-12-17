<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    
    public function store(StoreArticleRequest $request)
    {
        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return new ArticleResource($article);
    }
    
    public function update(UpdateArticleRequest $request, Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $article->update($request->only(['title', 'content']));
        

        return new ArticleResource($article);
    }

    
    public function destroy(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $article->delete();
        return response()->json(['message' => 'Article deleted']);
    }

    
    public function index()
    {
        $articles = Article::with('user')->latest()->get();
        return ArticleResource::collection($articles);
    }

    
    public function show(Article $article)
    {
        $article->load('user','comments.user');
        return new ArticleResource($article);
    }
}