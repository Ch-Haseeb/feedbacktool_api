<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks'; 

    protected $fillable = ['title', 'description', 'category', 'attachment', 'user_id'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function hasVotedByUser(User $user)
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
