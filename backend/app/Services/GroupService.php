<?php

namespace App\Services;

use App\Enums\GroupRole;
use App\Models\Group;
use App\Models\User;
use App\Traits\UploadFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupService
{
    use UploadFiles;

    public function createGroup(User $user, array $data): Group
    {
        return DB::transaction(function () use ($user, $data) {
            if (isset($data['logo'])) {
                $data['logo'] = $this->uploadFile($data['logo'], 'groups/logos', 'public');
            }

            $data['owner_id'] = $user->id;
            $group = Group::create($data);

            // Attach the creator as an admin
            $group->users()->attach($user->id, ['role' => GroupRole::ADMIN->value]);

            return $group;
        });
    }

    public function updateGroup(Group $group, array $data): Group
    {
        if (isset($data['logo'])) {
            if ($group->logo) {
                Storage::disk('public')->delete($group->logo);
            }
            $data['logo'] = $this->uploadFile($data['logo'], 'groups/logos', 'public');
        }

        $group->update($data);

        return $group;
    }

    public function deleteGroup(Group $group): void
    {
        if ($group->logo) {
            Storage::disk('public')->delete($group->logo);
        }

        $group->delete();
    }

    public function addMember(Group $group, string $email, string $role): void
    {
        $userToAdd = User::where('email', $email)->firstOrFail();

        if ($group->users()->where('user_id', $userToAdd->id)->exists()) {
            throw new \Exception("This user is already a member of this group.");
        }

        $group->users()->attach($userToAdd->id, ['role' => $role]);
    }
}
