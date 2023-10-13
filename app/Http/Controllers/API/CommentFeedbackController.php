<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentFeedbackController extends Controller
{
    public function index(){

        $comments=Comment::with(['user','feedback'])->get();
        return response()->json(['comment' => $comments]);


    }

    public function store(CommentRequest $request, $feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);

        $comment = new Comment([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        $feedback->comments()->save($comment);

        return response()->json(['message' => 'Comment added successfully']);
    }

    public function update(CommentRequest $request, $feedbackId, $commentId)
    {
        $feedback = Feedback::findOrFail($feedbackId);

        $comment = $feedback->comments()->findOrFail($commentId);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return response()->json(['message' => 'Comment updated successfully']);
    }

    public function destroy($feedbackId, $commentId)
    {
        $feedback = Feedback::findOrFail($feedbackId);

        $comment = $feedback->comments()->findOrFail($commentId);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
