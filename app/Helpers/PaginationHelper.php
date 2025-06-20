<?php

declare(strict_types=1);

namespace App\Helpers;

use App\DTOs\PaginationDto;

final class PaginationHelper
{

    public static function pageQueryOptions(object $request): PaginationDto
    {
        return PaginationDto::make($request);
    }
}
