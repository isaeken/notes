<?php

namespace App\Http\Controllers\Web;

use App\Enums\NoteType;
use App\Enums\States;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;

class NoteController extends Controller
{
    /**
     * NoteController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        return redirect()->route('web.home.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('web.note.create');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, int $id)
    {
        $note = Note::findOrFail($id);
        abort_if($note->state != States::Active, 404);
        abort_if($note->type == NoteType::Private && $note->user_id != Auth::id(), 403);
        $comments = $note->comments()->where('state', States::Active)->get();
        return view('web.note.show', compact('note', 'comments'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, int $id)
    {
        $note = Note::findOrFail($id);
        abort_if($note->state != States::Active, 404);
        abort_if(Auth::user()->type != UserType::Administrator && $note->user_id != Auth::id(), 403);
        return view('web.note.edit', compact('note'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, int $id)
    {
        $note = Note::findOrFail($id);
        abort_if($note->state != States::Active, 404);
        abort_if(Auth::user()->type != UserType::Administrator && $note->user_id != Auth::id(), 403);
        return view('web.note.delete', compact('note'));
    }
}
