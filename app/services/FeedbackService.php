<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Feedback;

class FeedbackService
{

    public function saveFeedback($data)
    {
        try {
            if (isset($data['attachment'])) {
                $attachmentPath = $this->saveAttachment($data['attachment']);
                $data['attachment'] = $attachmentPath;
            }

            $feedback = Feedback::create($data);

            return ['success' => true, 'feedback' => $feedback];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to save feedback', $e->getMessage()];
        }
    }

    private function saveAttachment($attachment)
    {
        $filename = uniqid() . '.' . $attachment->getClientOriginalExtension();

        $attachmentPath = $attachment->storeAs('public/feedback_attachments', $filename);

        return str_replace('public/', '', $attachmentPath);
    }

    public function listFeedback($category = null, $sort = null)
    {
        $query = Feedback::with(['user', 'votes.user']);

        if ($category) {
            $query->where('category', $category);
        }

        if ($sort) {
            $query->orderBy($sort, 'desc');
        }

        return $query->paginate(10);
    }

    
    public function updateFeedback($feedback, $data)
    {
        try {
            if (isset($data['attachment'])) {
                $attachmentPath = $this->updateOrReplaceAttachment($feedback, $data['attachment']);
                $data['attachment'] = $attachmentPath;
            }

            $feedback->update($data);

            return ['success' => true, 'message' => 'Feedback updated successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to update feedback'];
        }
    }

    private function updateOrReplaceAttachment($feedback, $newAttachment)
    {
        if ($feedback->attachment) {
            Storage::delete('public/feedback_attachments/' . $feedback->attachment);
        }

        return $this->saveAttachment($newAttachment);
    }

    public function deleteFeedback($feedback)
    {
        try {
            if ($feedback->attachment) {
                Storage::delete('public/feedback_attachments/' . $feedback->attachment);
            }

            $feedback->delete();

            return ['success' => true, 'message' => 'Feedback deleted successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Failed to delete feedback'];
        }
    }

}
