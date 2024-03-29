<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        Comment::create([
            ...$data,
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            ]);

        return redirect($post->showRoute())
            ->banner('Comment added.');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:2500']]);

        Auth::user()->can('update', $comment) ?: abort(403);

        $comment->update($data);

        return redirect($comment->post->showRoute( ['page' => $request->query('page')]))
            ->banner('Comment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Comment $comment): RedirectResponse
    {
        Auth::user()->can('delete', $comment) ?: abort(403);

        $comment->delete();

        return redirect($comment->post->showRoute( ['page' => $request->query('page')]))
            ->banner('Comment deleted.');
    }
}
