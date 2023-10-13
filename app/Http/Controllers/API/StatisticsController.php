<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Feedback;
use App\Models\User;

class StatisticsController extends Controller
{
    public function countStatistics()
    {
        $userCount = User::count();
        $feedbackCount = Feedback::count();
        $commentCount = Comment::count();

        $statistics = [
            'user_count' => $userCount,
            'feedback_count' => $feedbackCount,
            'comment_count' => $commentCount,
        ];

        return response()->json($statistics);
    }
}
