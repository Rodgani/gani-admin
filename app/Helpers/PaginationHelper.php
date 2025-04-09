<?php

namespace App\Helpers;

use App\Dtos\PaginationDto;

class PaginationHelper
{

    public static function pageQueryOptions($request): PaginationDto
    {
        return PaginationDto::fromRequest($request);
    }
}

