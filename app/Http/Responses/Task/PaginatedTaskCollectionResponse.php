<?php

declare(strict_types=1);

namespace App\Http\Responses\Task;

use App\Dto\Entity\TaskEntity;
use App\Http\Responses\Common\MetaResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class PaginatedTaskCollectionResponse extends Data
{
    /**
     * @param  Collection<array-key, TaskResourceResponse>  $items
     */
    public function __construct(
        public Collection $items,
        public MetaResponse $meta
    ) {}

    /**
     * @param  LengthAwarePaginator<array-key, TaskEntity>  $paginator
     */
    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        /** @var Collection<array-key, TaskEntity> $collection */
        $collection = $paginator->getCollection();

        return new self(
            items: $collection->map(static function (TaskEntity $task): TaskResourceResponse {
                return TaskResourceResponse::fromEntity($task);
            }),
            meta: new MetaResponse(
                currentPage: $paginator->currentPage(),
                perPage: $paginator->perPage(),
                total: $paginator->total(),
            )
        );
    }
}
