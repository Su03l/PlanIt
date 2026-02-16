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
    // this for http responses
    use HttpResponses;

    // this for user service 
    protected UserService $userService;

    // this for user service constructor
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // this for show profile request and return response
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
