<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteArticleRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use AuthorizesRequests;
    public function store(StoreArticleRequest $request)
    {
        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return (new ArticleResource($article))->response()->setStatusCode(201);
    }
    
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);
        $article->update($request->validated());

        return (new ArticleResource($article))->response()->setStatusCode(200);
    }

    
    public function destroy(DeleteArticleRequest $request ,Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();
        return response()->noContent();
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