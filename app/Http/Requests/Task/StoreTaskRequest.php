<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Enum\TaskPriorityEnum;
use Kr0lik\DtoToSwagger\Contract\JsonRequestInterface;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class StoreTaskRequest extends Data implements JsonRequestInterface
{
    public function __construct(
        public string $title,
        public TaskPriorityEnum $priority,
        public ?int $user_id,
        public ?string $description = null,
    ) {}

    /**
     * @return string[]
     */
    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'title' => 'required|string|min:1|max:255',
        ];
    }

    /**
     * @return string[]
     */
    public static function messages(): array
    {
        return [
            'title.max' => 'Title cannot be longer than 255 characters',
            'title.min' => 'Title cannot be less than 1 character',
        ];
    }
}
