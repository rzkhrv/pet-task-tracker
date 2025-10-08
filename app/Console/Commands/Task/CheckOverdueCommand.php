<?php

declare(strict_types=1);

namespace App\Console\Commands\Task;

use App\Enum\TaskNotificationTypeEnum;
use App\Jobs\SendTaskNotificationJob;
use App\Services\TaskService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckOverdueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-overdue
                            {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find overdue tasks and mention about it';

    /**
     * Execute the console command.
     */
    public function handle(
        TaskService $taskService,
    ): int {
        $isDryOption = $this->isDryOption();
        $overdueTasksIds = $taskService->getAllOverdueIds();

        if ($isDryOption) {
            $this->info('Find overdue tasks count: '.count($overdueTasksIds));

            return 0;
        }

        DB::beginTransaction();

        try {
            foreach ($overdueTasksIds as $overdueTaskId) {
                $taskService->storeOverdueComment($overdueTaskId);
            }
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        $this->pushJobsForOverdueTasks($overdueTasksIds);

        return 0;
    }

    /**
     * @param  array<int>  $ids
     */
    private function pushJobsForOverdueTasks(array $ids): void
    {
        foreach ($ids as $id) {
            SendTaskNotificationJob::dispatch(
                taskId: $id,
                notificationType: TaskNotificationTypeEnum::Overdue,
            );
        }
    }

    private function isDryOption(): bool
    {
        $dryOption = $this->option('dry-run');

        return $dryOption === true;
    }
}
