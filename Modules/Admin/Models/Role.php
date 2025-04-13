<?php

namespace Modules\Admin\Models;

use App\Traits\HandleTimezone;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HandleTimezone;
    protected $guarded = ["id"];
}
