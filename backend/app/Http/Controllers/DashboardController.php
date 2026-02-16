<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Group;
use App\Services\StatsService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use HttpResponses;

    protected StatsService $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    /**
     * General stats for the logged-in user across all groups.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $data = [
            'total_groups' => $user->groups()->count(),
            'total_my_tasks' => $user->tasks()->count(), // المهام المسندة لي
            'pending_my_tasks' => $user->tasks()->where('status', TaskStatus::PENDING)->count(),
            'recent_activities' => \App\Models\Activity::where('user_id', $user->id)
                                                       ->latest()
                                                       ->limit(5)
                                                       ->get(),
        ];

        return $this->success($data, 'User general stats retrieved successfully.');
    }

    /**
     * Detailed stats for a specific group.
     */
    public function groupStats(Group $group)
    {
        $this->authorize('view', $group);

        $stats = $this->statsService->getGroupStats($group);

        return $this->success($stats, 'Group detailed stats retrieved successfully.');
    }
}
