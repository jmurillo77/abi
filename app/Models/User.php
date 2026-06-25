<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'IdPersona',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    public function persona()
    {
        return $this->belongsTo(\App\Models\admin\Persona::class, 'IdPersona', 'IdPersona');
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'IdRol', 'IdRol');
    }

    public function permittedSubmenus()
    {
        if (! $this->relationLoaded('role')) {
            $this->load('role.submenus');
        }

        return $this->role?->submenus ?? collect();
    }

    public function canViewMenu(int $menuId): bool
    {
        return $this->permittedSubmenus()->contains('IdMenu', $menuId);
    }

    public function canViewSubmenu(int $submenuId): bool
    {
        return $this->permittedSubmenus()->contains('IdSubMenu', $submenuId);
    }

    public function canCreateMenu(int $menuId): bool
    {
        return false;
    }

    public function canEditMenu(int $menuId): bool
    {
        return false;
    }

    public function canDeleteMenu(int $menuId): bool
    {
        return false;
    }

    public function adminlte_image(){
        return 'https://picsum.photos/300/300';
    }
    public function adminlte_desc(){
        return 'Administrador';
    }
    public function adminlte_profile_url(){
        return 'profile/username';
    }
}
