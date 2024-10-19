<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    /**
     * @param $attributes
     * @return false|string
     */
    public function login($attributes): bool|string
    {
        $credentials = [
            'email' => data_get($attributes, 'email'),
            'password' => data_get($attributes, 'password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return $user->createToken('AuthToken')->accessToken;
        }

        return false;
    }

    /**
     * @param $request
     */
    public function logout($request)
    {
        $request->user()->token()->revoke();
    }


    /**
     * @param $attributes
     * @return mixed
     * @throws \Exception
     */
    public function register($attributes)
    {
        try {
            return User::create([
                'name' => data_get($attributes, 'name'),
                'role' => 'author',
                'email' => data_get($attributes, 'email'),
                'password' => bcrypt(data_get($attributes, 'password')),
                'email_verified_at' => now(),
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
