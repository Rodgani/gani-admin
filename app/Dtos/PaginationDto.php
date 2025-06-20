<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class PaginationDto
{
    /**
     * Summary of __construct
     * @param bool $paginated
     * @param int $perPage
     * @param string $column
     * @param string $direction
     */
    public function __construct(
        public bool $paginated,
        public int $perPage,
        public string $column,
        public string $direction
    ) {
    }

    public static function make($request): self
    {
        return new self(
            (bool) ($request->paginated ?? false),
            (int) ($request->per_page ?? 10),
            (string) ($request->order_by ?? 'updated_at'),
            (string) ($request->order_direction ?? 'desc')
        );
    }
}
