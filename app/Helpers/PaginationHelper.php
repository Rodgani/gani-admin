<?php

namespace App\Helpers;

use App\DTO\PaginationDTO;

class PaginationHelper
{
    /**
     * Summary of pageQueryOptions
     * @param mixed $request
     * @return \App\DTO\PaginationDTO
     */
    public static function pageQueryOptions($request): PaginationDTO
    {
        return PaginationDTO::fromRequest($request);
    }
}

