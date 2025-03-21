<?php

namespace App\Helper;

use App\DTO\PaginationOptionsDTO;

class PaginationHelper
{
    /**
     * Summary of pageQueryOptions
     * @param mixed $request
     * @return PaginationOptionsDTO
     */
    public static function pageQueryOptions($request): PaginationOptionsDTO
    {
        return PaginationOptionsDTO::fromRequest($request);
    }
}

