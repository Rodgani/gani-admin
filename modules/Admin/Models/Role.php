<?php

namespace Modules\Admin\Models;

use App\Traits\HandleTimezone;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function createdAt(): Attribute
    {
        return Attribute::get(fn($value) => $this->convertTimezoneToUserTimezone($value));
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::get(fn($value) => $this->convertTimezoneToUserTimezone($value));
    }
}
