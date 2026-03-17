<?php

namespace Administration\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Administration\Observers\UserObserver;
use Shared\Scopes\HasActiveScope;
use Shared\Traits\BelongsToCompany;
use Shared\Traits\Loggable;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Loggable, Notifiable, SoftDeletes, HasRoles, BelongsToCompany, HasActiveScope;

    protected $table = 'sec_users';

    /**
     * The guard name for Spatie permissions.
     *
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'avatar_path',
    ];

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
     * The attributes that should be appended.
     *
     * @var list<string>
     */
    protected $appends = [
        'avatar_url',
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
            'password' => 'hashed'
        ];
    }

    /**
     * @return string|null
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar_path) {
            return null;
        }

        return Storage::disk('public')->url($this->avatar_path);
    }

    /**
     * Get the social accounts linked to this user.
     *
     * @return HasMany
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(\Auth\Models\SocialAccount::class);
    }

    /**
     * Get the morph class name for the model.
     * Must match the enforceMorphMap key so Spatie permissions work
     * with both Administration\User and Auth\User (which extends this).
     *
     * @return string
     */
    public function getMorphClass(): string
    {
        return 'user';
    }
}
