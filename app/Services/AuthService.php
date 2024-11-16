<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = auth()->login($user);

        return ['token' => $token];
    }

    public function login(array $credentials): array
    {
        if (!$token = auth()->attempt($credentials)) {
            throw new UnauthorizedException();
        }

        return ['token' => $token];
    }
}
