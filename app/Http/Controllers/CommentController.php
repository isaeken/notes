<?php

namespace App\Http\Controllers;

use App\Enums\NoteType;
use App\Enums\States;
use App\Enums\UserType;
use App\Models\Comment;
use App\Models\Content;
use App\Models\Note;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * NoteController constructor.
     */
    public function __construct()
    {

    }

    /**
     * Get all comments.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('comment:read'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        return response()->json([
            'success' => true,
            'response' => Comment::where('state', States::Active)->get(),
        ]);
    }

    /**
     * Show a comment.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('comment:read'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $comment = Comment::findOrFail($id);
        return response()->json([
            'success' => true,
            'response' => $comment,
        ]);
    }

    /**
     * Store a new comment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('comment:create'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $request->validate([
            'note_id' => 'required|exists:notes,id',
            'content' => 'required|min:2',
        ]);
        $comment = Comment::create([
            'state' => States::Active,
            'user_id' => $request->user()->id,
            'note_id' => $request->note_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'content' => $request->content,
        ]);
        return response()->json([
            'success' => true,
            'response' => $comment,
        ]);
    }

    /**
     * Update a comment.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('comment:update'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $request->validate([ 'content' => 'required|min:2' ]);
        $comment = Comment::findOrFail($id);
        abort_if($comment->state != States::Active, 404);
        abort_if($request->user()->type != UserType::Administrator && $comment->user_id != $request->user()->id, 403);
        $comment->update([ 'content' => $request->content ]);
        return response()->json([
            'success' => true,
            'response' => $comment,
        ]);
    }

    /**
     * Destroy a comment.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('comment:delete'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $comment = Comment::findOrFail($id);
        abort_if($request->user()->type != UserType::Administrator && $comment->user_id != $request->user()->id, 403);
        abort_if($comment->state != States::Active, 404);
        $comment->update([ 'state' => States::Deleted ]);
        return response()->json([
            'success' => true,
            'response' => 'ok',
        ]);
    }
}
