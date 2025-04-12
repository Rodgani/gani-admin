<?php

namespace App\Models\Admin;
use App\Traits\HandleTimezone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HandleTimezone;
    protected $guarded = ["id"];
}
