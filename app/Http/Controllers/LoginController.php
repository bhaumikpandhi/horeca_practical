<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    protected UserRepository $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $token = $this->repository->login($request->all());

        if ($token) {
            return response()->json([
                'access_token' => $token,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $this->repository->logout($request);

        return response()->json(['Logout success']);
    }
}
