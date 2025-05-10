<?php

namespace Modules\Admin\Models;

use App\Traits\HandleTimezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Database\Factories\RoleFactory;

class Role extends Model
{
    use HandleTimezone, HasFactory;
    protected $guarded = ["id"];

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}
