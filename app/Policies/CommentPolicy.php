<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        $userCheck = ($user->id === $comment->user_id);

        return  $userCheck;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        $userCheck = ($user->id === $comment->user_id);

        $commentCheck = $comment->created_at->isAfter(now()->subHour());

        return  $userCheck && $commentCheck;
    }
}
