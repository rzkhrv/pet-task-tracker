<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\Commands\Task\CheckOverdueCommand;
use App\Events\TaskCreatedEvent;
use App\Events\TaskUpdatedEvent;
use App\Jobs\SendTaskNotificationJob;
use App\Listeners\CreateCommentForCompletedTaskListener;
use App\Listeners\ProcessNewTaskListener;
use App\Listeners\SendNotificationWhenStatusChangedListener;
use App\Repositories\TaskRepository;
use App\Services\TaskNotificationService;
use App\Services\TaskService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bindMethod(
            [SendTaskNotificationJob::class, 'handle'],
            static function (SendTaskNotificationJob $job, Application $app) {
                $job->handle($app->make(TaskNotificationService::class));
            }
        );

        $this->app->bindMethod(
            [CheckOverdueCommand::class, 'handle'],
            static function (CheckOverdueCommand $command, Application $app) {
                $command->handle($app->make(TaskService::class));
            }
        );

        $this->app->bind(TaskRepository::class, function () {
            $overdueDays = is_int(config('app.task.overdue_days'))
                ? config('app.task.overdue_days')
                : 7;

            return new TaskRepository(
                overdueDays: $overdueDays,
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();

        Event::listen(TaskCreatedEvent::class, [
            ProcessNewTaskListener::class,
        ]);

        Event::listen(TaskUpdatedEvent::class, [
            CreateCommentForCompletedTaskListener::class,
            SendNotificationWhenStatusChangedListener::class,
        ]);
    }
}
