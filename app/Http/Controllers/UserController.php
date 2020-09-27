<?php

namespace App\Http\Controllers;

use App\Enums\LogLevel;
use App\Enums\States;
use App\Enums\UserType;
use App\Models\User;
use App\Models\UserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {

    }

    /**
     * Get all users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('user:read'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        UserAction::create([
            'level' => LogLevel::Notice,
            'user_id' => $request->user()->id,
            'message' => __('Fetched all users. :user', [ 'user' => $request->user()->email ])
        ]);
        return response()->json([
            'success' => true,
            'response' => User::where('state', States::Active)->get(),
        ]);
    }

    /**
     * Show a user.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id)
    {
        $request->validate([ 'token' => 'required' ]);
        $request->user()->withAccessToken($request->user()->tokens()->where('token', $request->token)->first());
        abort_if(!$request->user()->tokenCan('user:read'), 403);

        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $user = User::findOrFail($id);
        UserAction::create([
            'level' => LogLevel::Notice,
            'user_id' => $request->user()->id,
            'message' => __('Fetched user. :fetch - :user', [ 'fetch' => $user->id, 'user' => $request->user()->email ])
        ]);
        return response()->json([
            'success' => true,
            'response' => $user,
        ]);
    }
}
