<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $type, int $id)
    {
        $likeable = $this->findLikeable($type, $id);

        Gate::authorize('create', [Like::class, $likeable]);

        $likeable->likes()->create([
            'user_id' => $request->user()->id,
        ]);

        // 確保更新 likes_count 屬性
        $likeable->increment('likes_count');

        return back();
    }

    protected function findLikeable(string $type, int $id): Model
    {
        $modelName = Relation::getMorphedModel($type);

        if ($modelName === null) {
            throw new ModelNotFoundException();
        }

        $likeable = $modelName::findOrFail($id);

        return $likeable;
    }

    /**
     * Display the specified resource.
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        //
    }
}
