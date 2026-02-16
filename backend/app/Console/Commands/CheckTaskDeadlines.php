<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Notifications\SystemNotification;
use Illuminate\Console\Command;

class CheckTaskDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-task-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for tasks approaching their deadlines and notify assignees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // الأوقات المحددة بالدقائق
        $intervals = [
            '1 day' => 1440,
            '12 hours' => 720,
            '4 hours' => 240,
            '2 hours' => 120,
            '1 hour' => 60,
            '30 minutes' => 30,
            '10 minutes' => 10,
            '5 minutes' => 5,
            '1 minute' => 1
        ];

        foreach ($intervals as $label => $minutes) {
            // البحث عن المهام التي تنتهي في هذا النطاق الزمني بالضبط
            $tasks = Task::where('status', '!=', TaskStatus::COMPLETED->value)
                ->whereNotNull('due_date')
                ->whereBetween('due_date', [
                    now()->addMinutes($minutes)->startOfMinute(),
                    now()->addMinutes($minutes)->endOfMinute()
                ])->get();

            foreach ($tasks as $task) {
                if ($task->assigned_to) {
                    $task->assignee->notify(new SystemNotification(
                        "Deadline Warning!",
                        "The task [{$task->title}] is due in {$label}.",
                        "deadline_warning",
                        "/tasks/{$task->uuid}"
                    ));
                }
            }
        }

        $this->info('Task deadline checks completed.');
    }
}
