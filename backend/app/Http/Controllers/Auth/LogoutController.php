<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request, AuthService $authService)
    {
        $authService->logout($request->user());

        return $this->success(null, 'Logged out successfully.');
    }
}
