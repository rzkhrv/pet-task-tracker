<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Api;

use Tests\TestCase;

final class TaskControllerTest extends TestCase
{
    public function test_get_tasks_success()
    {
        $response = $this->get('/api/tasks');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'items' => [
                    '*' => [
                        'id',
                        'userId',
                        'title',
                        'description',
                        'status',
                        'priority',
                        'createdAt',
                    ],
                ],
                'meta' => [
                    'currentPage',
                    'perPage',
                    'total',
                ],
            ]);
    }

    public function test_get_tasks_failed()
    {
        $response = $this->get('/api/tasks?filters[status]=abracadabra');

        $response
            ->assertStatus(422);
    }
}
