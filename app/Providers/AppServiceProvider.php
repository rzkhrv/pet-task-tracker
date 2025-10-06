<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\TaskCreatedEvent;
use App\Jobs\SendTaskNotificationJob;
use App\Listeners\ProcessNewTaskListener;
use App\Services\TaskNotificationService;
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
    }
}
