<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\admin\Menu;
use App\Models\admin\Submenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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
        'IdRol',
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
        return $this->belongsTo(\App\Models\matriz\Persona::class, 'IdPersona', 'IdPersona');
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'IdRol', 'IdRol');
    }

    public function menus()
    {
        $pivotTable = $this->permissionPivotTable('menu_user');

        return $this->belongsToMany(
            Menu::class,
            $pivotTable,
            'user_id',
            'menu_id',
            'id',
            'IdMenu'
        )->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete'])
            ->withTimestamps();
    }

    public function submenus()
    {
        $pivotTable = $this->permissionPivotTable('submenu_user');

        return $this->belongsToMany(
            Submenu::class,
            $pivotTable,
            'user_id',
            'submenu_id',
            'id',
            'IdSubMenu'
        )->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete'])
            ->withTimestamps();
    }

    public function permittedMenus()
    {
        if (! $this->hasPermissionPivotTable('menu_user')) {
            if (! $this->relationLoaded('role')) {
                $this->load('role.menus');
            }

            return $this->role?->menus ?? collect();
        }

        $directMenus = $this->menus()->wherePivot('can_view', true)->get();

        if ($directMenus->isNotEmpty()) {
            return $directMenus;
        }

        if (! $this->relationLoaded('role')) {
            $this->load('role.menus');
        }

        return $this->role?->menus ?? collect();
    }

    public function permittedSubmenus()
    {
        if (! $this->hasPermissionPivotTable('submenu_user')) {
            if (! $this->relationLoaded('role')) {
                $this->load('role.submenus');
            }

            return $this->role?->submenus ?? collect();
        }

        $directSubmenus = $this->submenus()->wherePivot('can_view', true)->get();

        if ($directSubmenus->isNotEmpty()) {
            return $directSubmenus;
        }

        if (! $this->relationLoaded('role')) {
            $this->load('role.submenus');
        }

        return $this->role?->submenus ?? collect();
    }

    public function canViewMenu(int $menuId): bool
    {
        return $this->permittedMenus()->contains('IdMenu', $menuId)
            || $this->permittedSubmenus()->contains('IdMenu', $menuId);
    }

    public function canViewSubmenu(int $submenuId): bool
    {
        return $this->permittedSubmenus()->contains('IdSubMenu', $submenuId);
    }

    public function canCreateSubmenu(int $submenuId): bool
    {
        return $this->hasDirectSubmenuPermission($submenuId, 'can_create');
    }

    public function canEditSubmenu(int $submenuId): bool
    {
        return $this->hasDirectSubmenuPermission($submenuId, 'can_edit');
    }

    public function canDeleteSubmenu(int $submenuId): bool
    {
        return $this->hasDirectSubmenuPermission($submenuId, 'can_delete');
    }

    public function canSubmenuAction(string $action, ?string $routeName = null): bool
    {
        $routeName = $routeName ?: request()->route()?->getName();

        if (! $routeName) {
            return false;
        }

        $submenu = $this->resolveSubmenuByRoute($routeName);

        if (! $submenu) {
            // If submenu is not configured, do not block route by default.
            return true;
        }

        $permissionField = match ($action) {
            'view' => 'can_view',
            'create' => 'can_create',
            'edit' => 'can_edit',
            'delete' => 'can_delete',
            default => 'can_view',
        };

        if ($this->hasPermissionPivotTable('submenu_user') && $this->submenus()->exists()) {
            $directSubmenu = $this->submenus()
                ->where('submenus.IdSubMenu', $submenu->IdSubMenu)
                ->first();

            if (! $directSubmenu) {
                return false;
            }

            return (bool) ($directSubmenu->pivot->{$permissionField} ?? false);
        }

        // Legacy fallback based on role submenu assignment.
        return $this->permittedSubmenus()->contains('IdSubMenu', $submenu->IdSubMenu);
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

    protected function hasDirectSubmenuPermission(int $submenuId, string $permission): bool
    {
        if (! $this->hasPermissionPivotTable('submenu_user')) {
            return false;
        }

        $submenu = $this->submenus()
            ->where('submenus.IdSubMenu', $submenuId)
            ->first();

        if (! $submenu) {
            return false;
        }

        return (bool) ($submenu->pivot->{$permission} ?? false);
    }

    protected function resolveSubmenuByRoute(string $routeName): ?Submenu
    {
        $baseRoute = preg_replace('/\.(crear|create|store|show|edit|update|destroy)$/', '.index', $routeName) ?: $routeName;
        $segments = explode('.', $baseRoute);
        $withoutPrefix = count($segments) > 2 ? implode('.', array_slice($segments, 1)) : $baseRoute;

        $candidates = collect([$routeName, $baseRoute, $withoutPrefix])
            ->filter()
            ->unique()
            ->values()
            ->all();

        return Submenu::query()
            ->whereIn('Ruta', $candidates)
            ->where('Activo', 1)
            ->first();
    }

    protected function permissionPivotTable(string $table): string
    {
        $connections = $this->pivotCandidateConnections();

        foreach ($connections as $connection) {
            if ($this->connectionHasTable($connection, $table)) {
                $databaseName = (string) config("database.connections.{$connection}.database");

                return $databaseName !== '' ? $databaseName.'.'.$table : $table;
            }
        }

        $defaultConnection = (string) config('database.default');
        $databaseName = (string) config("database.connections.{$defaultConnection}.database");

        return $databaseName !== '' ? $databaseName.'.'.$table : $table;
    }

    protected function hasPermissionPivotTable(string $table): bool
    {
        foreach ($this->pivotCandidateConnections() as $connection) {
            if ($this->connectionHasTable($connection, $table)) {
                return true;
            }
        }

        return false;
    }

    protected function pivotCandidateConnections(): array
    {
        return collect([
            (string) config('database.default'),
            'negocio',
            'matriz',
        ])->filter()->unique()->values()->all();
    }

    protected function connectionHasTable(string $connection, string $table): bool
    {
        try {
            return Schema::connection($connection)->hasTable($table);
        } catch (\Throwable $exception) {
            return false;
        }
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
