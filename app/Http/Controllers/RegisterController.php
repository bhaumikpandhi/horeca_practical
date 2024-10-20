<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Repository\UserRepository;

class RegisterController extends Controller
{
    protected UserRepository $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->repository->register($request->all());

            return response()->json([
                'user' => $user
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }


    }
}
