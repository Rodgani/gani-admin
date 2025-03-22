<?php

namespace App\DTO;

use Illuminate\Http\Request;

class PaginationDTO
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

    /**
     * Summary of fromRequest
     * @param \Illuminate\Http\Request $request
     * @return PaginationDTO
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            filter_var($request->paginated, FILTER_VALIDATE_BOOLEAN),
            (int) ($request->per_page ?? 10),
            (string) ($request->order_by ?? 'updated_at'),
            (string) ($request->order_direction ?? 'desc')
        );
    }
}
