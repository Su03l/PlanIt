<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\AddMemberRequest;
use App\Http\Requests\Group\StoreGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Services\GroupService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    use HttpResponses;

    protected GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {
        // Pagination added for scalability
        $groups = $request->user()->groups()->with('owner')->paginate(10);

        return GroupResource::collection($groups);
    }

    public function store(StoreGroupRequest $request)
    {
        $group = $this->groupService->createGroup(
            $request->user(),
            $request->validated()
        );

        return $this->success(
            new GroupResource($group),
            'Group created successfully',
            201
        );
    }

    public function show(Group $group)
    {
        $this->authorize('view', $group);

        return $this->success(
            new GroupResource($group->load('owner')),
            'Group retrieved successfully'
        );
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        $this->authorize('update', $group);

        $group = $this->groupService->updateGroup(
            $group,
            $request->validated()
        );

        return $this->success(
            new GroupResource($group),
            'Group updated successfully'
        );
    }

    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $this->groupService->deleteGroup($group);

        return $this->success(
            null,
            'Group deleted successfully'
        );
    }

    public function members(Group $group)
    {
        $this->authorize('view', $group);

        // Pagination for members as well
        $members = $group->users()->paginate(20);

        return UserResource::collection($members);
    }

    public function addMember(AddMemberRequest $request, Group $group)
    {
        $this->authorize('addMember', $group);

        try {
            $this->groupService->addMember(
                $group,
                $request->email,
                $request->role
            );

            return $this->success(null, 'Member added successfully.');
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 422);
        }
    }
}
