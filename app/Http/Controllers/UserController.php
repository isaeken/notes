<?php

namespace App\Http\Controllers;

use App\Enums\States;
use App\Enums\UserType;
use App\Models\User;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $id)
    {
        abort_if($request->user()->type == UserType::Banned || $request->user()->state != States::Active, 403);
        $user = User::findOrFail($id);
        return response()->json([
            'success' => true,
            'response' => $user,
        ]);
    }
}
