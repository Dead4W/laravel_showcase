<?php

namespace App\User\Controllers;

use App\Common\DeepJsonResource\DeepJsonResource;
use App\Common\Http\Controllers\Controller;
use App\Common\Http\Resources\ResponseResource;
use App\User\Models\User;
use App\User\Requests\LoginRequest;
use App\User\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{

    public function register(RegisterRequest $request): DeepJsonResource {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        return new ResponseResource($user);
    }

    public function login(LoginRequest $request) {
        $credentials = [
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Неверные учетные данные'], 401);
        }

        $user = Auth::user();

        /** @var NewAccessToken $token */
        $token = $user->createToken('Personal Access Token');

        return new ResponseResource(
            [
                'user' => $user,
                'token' => $token->plainTextToken,
            ]
        );
    }

}
