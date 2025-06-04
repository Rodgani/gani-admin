<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

use App\Traits\HandleTimezone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Database\Factories\RoleFactory;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property array $menus_permissions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class Role extends Model
{
    use HandleTimezone, HasFactory;

    protected function casts(): array
    {
        return [
            'menus_permissions' => 'array',
        ];
    }
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
