<?php

namespace App\Http\Controllers;

use App\Enums\NoteType;
use App\Enums\States;
use App\Enums\UserType;
use App\Models\Content;
use App\Models\Note;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $note = Note::findOrFail($id);
        return response()->json([
            'success' => true,
            'response' => $note,
        ]);
    }

    /**
     * Store a new note.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $note = Note::findOrFail($id);
        abort_if($request->user()->type != UserType::Administrator && $note->user_id != $request->user()->id, 403);
        abort_if($note->state != States::Active, 404);
        $note->update([ 'state' => States::Deleted ]);
        return response()->json([
            'success' => true,
            'response' => 'ok',
        ]);
    }
}
