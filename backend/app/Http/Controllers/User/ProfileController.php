<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use HttpResponses;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show(Request $request)
    {
        return $this->success(
            new UserResource($request->user()),
            'Profile retrieved successfully'
        );
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $this->userService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return $this->success(
            new UserResource($user),
            'Profile updated successfully'
        );
    }
}
