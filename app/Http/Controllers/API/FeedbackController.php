<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use App\Services\FeedbackService;
use App\Models\Comment;
use App\Models\Feedback;
use App\Models\Vote;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    protected $feedbackService;

    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function index(Request $request)
    {
        $category = $request->input('category');
        $sort = $request->input('sort');

        $feedback = $this->feedbackService->listFeedback($category, $sort);

        return response()->json(['feedback' => $feedback]);
    }

    public function store(FeedbackRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $result = $this->feedbackService->saveFeedback($data);

        if ($result['success']) {


            return response()->json([
                'message' => 'Feedback submitted successfully',
                'feedback' => $result['feedback'],
            ], 201);
        } else {
            return response()->json(['error' => $result['error']], 500);
        }
    }

    public function update(FeedbackRequest $request, $id)
    {
        $feedback = Feedback::findOrFail($id);


        $data = $request->validated();

        $result = $this->feedbackService->updateFeedback($feedback, $data);

        if ($result['success']) {
            return response()->json(['message' => $result['message']]);
        } else {
            return response()->json(['error' => $result['error']], 500);
        }
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);

        $result = $this->feedbackService->deleteFeedback($feedback);

        if ($result['success']) {
            return response()->json(['message' => $result['message']]);
        } else {
            return response()->json(['error' => $result['error']], 500);
        }
    }

    public function vote(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $user = auth()->user();


        if (!$feedback->hasVotedByUser($user)) {
            $voteValue = $request->input('vote');
            Log::info("Vote Value: " . $voteValue);



            $vote = new Vote(['user_id' => $user->id, 'vote' => $voteValue]);
            $feedback->votes()->save($vote);


            return response()->json(['message' => 'Vote submitted successfully']);
        }

        return response()->json(['message' => 'You have already voted on this feedback'], 422);
    }
}
