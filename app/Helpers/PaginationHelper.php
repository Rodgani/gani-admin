<?php

namespace App\Helpers;

use App\Dto\PaginationDto;

class PaginationHelper
{

    public static function pageQueryOptions($request): PaginationDto
    {
        return PaginationDto::fromRequest($request);
    }
}

