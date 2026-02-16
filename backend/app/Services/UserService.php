<?php

namespace App\Services;

use App\Models\User;
use App\Traits\UploadFiles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    use UploadFiles;

    public function updateProfile(User $user, array $data): User
    {
        if (isset($data['avatar'])) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $this->uploadFile($data['avatar'], 'avatars', 'public');
        }

        if (isset($data['cover_image'])) {
            if ($user->cover_image) {
                Storage::disk('public')->delete($user->cover_image);
            }
            $data['cover_image'] = $this->uploadFile($data['cover_image'], 'covers', 'public');
        }

        $user->update($data);

        return $user;
    }

    public function changePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
