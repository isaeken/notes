<?php

namespace App\Http\Controllers\Web;

use App\Enums\NoteType;
use App\Enums\States;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $notes = Note::where('state', States::Active);
        if (Auth::user()->type != UserType::Administrator) $notes = $notes->where('type', '!=', NoteType::Private);
        $notes = $notes->get();
        return view('web.home.index',compact('notes'));
    }
}
