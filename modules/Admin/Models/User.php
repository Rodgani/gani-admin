<?php

declare(strict_types=1);

namespace Modules\Admin\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HandleTimezone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Admin\Database\Factories\UserFactory;
use Modules\Admin\Observers\UserObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property int $role_id
 * @property int $timezone
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

#[ObservedBy([UserObserver::class])]
final class User extends Authenticatable
{
    use HasFactory, Notifiable, HandleTimezone;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected function createdAt(): Attribute
    {
        return Attribute::get(fn($value) => $this->convertTimezoneToUserTimezone($value));
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::get(fn($value) => $this->convertTimezoneToUserTimezone($value));
    }

    protected function avatar(): Attribute
    {
        return Attribute::get(
            fn($value) => Storage::url($value ?: 'defaults/avatar.jpg')
        );
    }
}
