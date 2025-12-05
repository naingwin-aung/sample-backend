<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Services\Api\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(public AuthService $service)
    {
        //
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            ['user' => $user, 'token' => $token] = $this->service->login($request);

            return success([
                'token' => $token,
                'user'  => $user,
            ], 'Login successful.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function user()
    {
        try {
            $user = Auth::user();

            return success([
                'user' => $user,
            ], 'User retrieved successfully.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return success([], 'Successfully logged out.');
        } catch (Exception $e) {
            return error($e->getMessage());
        }
    }

    public function handleCallback(string $provider, Request $request)
    {
        try {
            ['user' => $user, 'token' => $token] = $this->service->handleCallback($provider, $request->token);

            return success([
                'token' => $token,
                'user'  => $user,
            ], 'Login successful.');
        } catch (Exception $e) {
            return error('Invalid Google Token.', 401);
        }
    }
}
