<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'slug',
        'menus_permissions'
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::get(fn($value) => Carbon::parse($value)->format('Y d F h:i A'));
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::get(fn($value) => Carbon::parse($value)->format('Y d F h:i A'));
    }
}
