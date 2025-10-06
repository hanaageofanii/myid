<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @method bool hasRole(array|string|\Spatie\Permission\Contracts\Role|\Illuminate\Support\Collection|int $roles, string|null $guard = null)
 */

class User extends Authenticatable implements HasTenants
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function canAccessFilament(): bool
    {
        return $this->hasRole('admin');
    }

    // public function teams(): BelongsToMany
    // {
    //     return $this->belongsToMany(Team::class);
    // }

    // public function getTenants(Panel $panel): Collection
    // {
    //     return $this->teams;
    // }

    // public function canAccessTenant(Model $tenant): bool
    // {
    //     return $this->teams()->whereKey($tenant)->exists();
    // }

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return true;
    // }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams->contains($tenant);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }


}
