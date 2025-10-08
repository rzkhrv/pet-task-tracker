<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Events\TaskCreatedEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $user_id
 * @property string $title
 * @property ?string $description
 * @property TaskStatusEnum $status
 * @property TaskPriorityEnum $priority
 * @property-read Collection<array-key, TaskComment> $comments
 * @property-read User $user
 */
final class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
    ];

    /** @phpstan-ignore-next-line */
    protected $dispatchesEvents = [
        'created' => TaskCreatedEvent::class,
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<TaskComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    protected function casts(): array
    {
        return [
            'status' => TaskStatusEnum::class,
            'priority' => TaskPriorityEnum::class,
        ];
    }
}
