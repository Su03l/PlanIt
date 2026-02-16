<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    use HttpResponses;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(ChangePasswordRequest $request)
    {
        $this->userService->changePassword(
            $request->user(),
            $request->validated()['password']
        );

        return $this->success(
            null,
            'Password changed successfully'
        );
    }
}
