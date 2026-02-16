<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use App\Traits\HttpResponses;

class LoginController extends Controller
{
    use HttpResponses;

    public function __invoke(LoginRequest $request, AuthService $authService)
    {
        $data = $authService->login($request->validated());

        if (!$data) {
            return $this->error(null, 'Invalid credentials', 401);
        }

        return $this->success($data, 'Logged in successfully.');
    }
}
