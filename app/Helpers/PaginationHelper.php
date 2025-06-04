<?php

declare(strict_types=1);

namespace App\Helpers;

use App\DTOs\PaginationDTO;

final class PaginationHelper
{

    public static function pageQueryOptions(object $request): PaginationDTO
    {
        return PaginationDTO::make($request);
    }
}
