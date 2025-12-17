<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
    public function index($articleId)
    {
        $comments = Comment::where('article_id', $articleId)->with('user')->get();
        return response()->json($comments);
    }

    public function store(Request $request, $articleId)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = Comment::create([
            'article_id' => $articleId,
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);

        return response()->json($comment, 201);
    }

       public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment); 

        $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return response()->json($comment, 200);
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
        return response()->json($comment);
    }
}