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
    // this for http responses
    use HttpResponses;

    // this for group service
    protected GroupService $groupService;

    // this for group service constructor
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    // this for show all groups request and return response
    public function index(Request $request)
    {
        // Pagination added for scalability
        $groups = $request->user()->groups()->with('owner')->paginate(10);

        return GroupResource::collection($groups);
    }

    // this for store group request and return response
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

    // this for show group request and return response
    public function show(Group $group)
    {
        $this->authorize('view', $group);

        return $this->success(
            new GroupResource($group->load('owner')),
            'Group retrieved successfully'
        );
    }

    // this for update group request and return response
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

    // this for delete group request and return response
    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $this->groupService->deleteGroup($group);

        return $this->success(
            null,
            'Group deleted successfully'
        );
    }

    // this for show group members request and return response
    public function members(Group $group)
    {
        $this->authorize('view', $group);

        // Pagination for members as well
        $members = $group->users()->paginate(20);

        return UserResource::collection($members);
    }

    // this for add group member request and return response
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
