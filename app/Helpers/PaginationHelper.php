<?php

namespace App\Helpers;

use App\DataTransferObjects\PaginationDTO;

class PaginationHelper
{

    public static function pageQueryOptions($request): PaginationDTO
    {
        return PaginationDTO::fromRequest($request);
    }
}

