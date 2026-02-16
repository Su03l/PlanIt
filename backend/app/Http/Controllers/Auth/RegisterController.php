<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\HttpResponses;


class RegisterController extends Controller
{
    use HttpResponses;

    public function __invoke(RegisterRequest $request, AuthService $authService)
    {
        $data = $authService->register($request->validated());

        return $this->success($data, 'Account registered and Workspace created successfully.', 201);
    }
}
