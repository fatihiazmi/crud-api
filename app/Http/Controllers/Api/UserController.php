<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $device = substr($request->userAgent() ?? '', 0, 255);

        return response()->json([
            'access_token' => $user->createToken($device)->plainTextToken,
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request)
    {
        $validData = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->user())],
        ]);

        auth()->user()->update($validData);

        return response()->json($validData, Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request)
    {
        auth()->user()->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
