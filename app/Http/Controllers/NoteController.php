<?php

namespace App\Http\Controllers;

use App\Enums\LogLevel;
use App\Enums\NoteType;
use App\Enums\States;
use App\Enums\UserType;
use App\Models\Content;
use App\Models\Note;
use App\Models\UserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * NoteController constructor.
     */
    public function __construct()
    {

    }

    /**
     * Get all notes.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('note:read'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        UserAction::create([
            'level' => LogLevel::Notice,
            'user_id' => $request->user()->id,
            'message' => __('Fetched all notes. :user', [ 'user' => $request->user()->email ])
        ]);
        return response()->json([
            'success' => true,
            'response' => Note::where('state', States::Active)
                ->where('type', '!=', NoteType::Private)
                ->latest()
                ->get(),
        ]);
    }

    /**
     * Show a note.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('note:read'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $note = Note::findOrFail($id);
        UserAction::create([
            'level' => LogLevel::Notice,
            'user_id' => $request->user()->id,
            'message' => __('Fetched note. :note - :user', [ 'note' => $note->id, 'user' => $request->user()->email ])
        ]);
        return response()->json([
            'success' => true,
            'response' => $note,
        ]);
    }

    /**
     * Store a new note.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('note:create'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $request->validate([
            'title' => 'required|min:2',
            'type' => 'required|in:'.implode(',', NoteType::getValues()),
            'content' => 'required|min:2',
        ]);
        $note = Note::create([
            'state' => States::Active,
            'type' => $request->type,
            'user_id' => $request->user()->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'comments' => $request->comments,
            'title' => $request->title,
        ]);
        $content = Content::create([
            'state' => States::Active,
            'user_id' => $request->user()->id,
            'note_id' => $note->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'content' => $request->content,
        ]);
        UserAction::create([
            'level' => LogLevel::Notice,
            'user_id' => $request->user()->id,
            'message' => __('Created note. :note - :user', [ 'note' => $note->id, 'user' => $request->user()->email ])
        ]);
        return response()->json([
            'success' => true,
            'response' => [
                'url' => route('web.notes.show', [ 'id' => $note->id ]),
                'id' => $note->id,
            ],
        ]);
    }

    /**
     * Update a note.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('note:update'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $request->validate([
            'title' => 'required|min:2',
            'type' => 'required|in:'.implode(',', NoteType::getValues()),
            'content' => 'required|min:2',
        ]);
        $note = Note::findOrFail($id);
        abort_if($note->state != States::Active, 404);
        abort_if($request->user()->type != UserType::Administrator && $note->user_id != $request->user()->id, 403);
        $note->update([
            'title' => $request->title,
            'type' => $request->type,
            'comments' => $request->comments,
        ]);
        $content = Content::create([
            'state' => States::Active,
            'user_id' => $request->user()->id,
            'note_id' => $note->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'content' => $request->content,
        ]);
        $response = [
            'url' => route('web.notes.show', [ 'id' => $note->id ]),
            'note' => $note,
            'content' => $content,
        ];
        UserAction::create([
            'level' => LogLevel::Notice,
            'user_id' => $request->user()->id,
            'message' => __('Updated note. :note - :user', [ 'note' => $note->id, 'user' => $request->user()->email ])
        ]);
        return response()->json([
            'success' => true,
            'response' => $response,
        ]);
    }

    /**
     * Destroy a note.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('note:delete'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $note = Note::findOrFail($id);
        abort_if($request->user()->type != UserType::Administrator && $note->user_id != $request->user()->id, 403);
        abort_if($note->state != States::Active, 404);
        $note->update([ 'state' => States::Deleted ]);
        UserAction::create([
            'level' => LogLevel::Notice,
            'user_id' => $request->user()->id,
            'message' => __('Deleted note. :note - :user', [ 'note' => $note->id, 'user' => $request->user()->email ])
        ]);
        return response()->json([
            'success' => true,
            'response' => 'ok',
        ]);
    }
}
