<?php
namespace App\Services\Api;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user  = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token,
            ];
        }

        return error('Invalid credentials.', 401);
    }

    public function handleCallback(string $provider, $token)
    {
        if (empty($token)) {
            throw new Exception('Token not provided.');
        }

        $providerUser = Socialite::driver($provider)->stateless()->userFromToken($token);

        $user = User::where('email', $providerUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'     => $providerUser->getName(),
                'email'    => $providerUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
            ]);
        }

        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}