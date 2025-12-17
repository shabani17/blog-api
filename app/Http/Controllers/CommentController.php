<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
    public function index(Article $article)
    {
        $comments = $article->comments()->with('user')->latest()->get();
        return CommentResource::collection($comments);
    }

    public function store(StoreCommentRequest $request, $articleId)
    {
        $comment = Comment::create([
            'article_id' => $articleId,
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);

        return new CommentResource($comment);
    }

       public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment); 
        $comment->update([
            'body' => $request->body,
        ]);

        return new CommentResource($comment);
    }


    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment); 

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.'], 200);
    }

    

    public function show(Comment $comment)
    {
        $comment->load('user', 'article');
        return new CommentResource($comment);
    }
}