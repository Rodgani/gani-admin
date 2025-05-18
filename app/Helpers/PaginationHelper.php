<?php

namespace App\Helpers;

use App\Dtos\PaginationDto;

class PaginationHelper
{

    public static function pageQueryOptions(object $request): PaginationDto
    {
        return PaginationDto::make($request);
    }
}

