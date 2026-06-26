<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    @php
        $resolveLink = function (?string $routeOrPath): string {
            $routeOrPath = trim((string) $routeOrPath);

            if ($routeOrPath === '') {
                return '#';
            }

            if (str_starts_with($routeOrPath, 'http://') || str_starts_with($routeOrPath, 'https://')) {
                return $routeOrPath;
            }

            if (\Illuminate\Support\Facades\Route::has($routeOrPath)) {
                return route($routeOrPath);
            }

            return \Illuminate\Support\Facades\URL::to($routeOrPath);
        };

        $sidebarItems = collect($adminlte->menu('sidebar'));
        $searchItems = $sidebarItems->filter(function ($item) {
            return is_array($item) && ($item['type'] ?? null) === 'sidebar-menu-search';
        })->values();

        $permittedSubmenus = collect();
        $selectedMenuId = (int) request()->query('menu_id', 0);

        if ($selectedMenuId > 0) {
            session(['selected_menu_id' => $selectedMenuId]);
        } else {
            $selectedMenuId = (int) session('selected_menu_id', 0);
        }

        if (auth()->check()) {
            $authUser = auth()->user()->loadMissing(['role.submenus']);
            $permittedSubmenuIds = $authUser->permittedSubmenus()
                ->pluck('IdSubMenu')
                ->unique()
                ->values()
                ->all();

            if (! empty($permittedSubmenuIds)) {
                $permittedSubmenus = \App\Models\admin\Submenu::query()
                    ->with('menu')
                    ->whereIn('IdSubMenu', $permittedSubmenuIds)
                    ->whereNotNull('IdMenu')
                    ->where('Activo', 1)
                    ->when($selectedMenuId > 0, function ($query) use ($selectedMenuId) {
                        $query->where('IdMenu', $selectedMenuId);
                    })
                    ->whereHas('menu', function ($query) {
                        $query->where('Activo', 1);
                    })
                    ->orderByRaw('COALESCE(IdMenu, 999999)')
                    ->orderByRaw('COALESCE(Orden, 999999)')
                    ->get()
                    ->map(function ($submenu) use ($resolveLink, $selectedMenuId) {
                        $submenu->link = $resolveLink($submenu->Ruta);
                        $submenu->link_with_menu = $selectedMenuId > 0
                            ? (str_contains($submenu->link, '?') ? $submenu->link.'&menu_id='.$selectedMenuId : $submenu->link.'?menu_id='.$selectedMenuId)
                            : $submenu->link;

                        return $submenu;
                    });
            }
        }
    @endphp

    <div class="sidebar">
        <style>
            .main-sidebar .brand-link {
                text-align: left;
                padding-left: .5rem;
            }

            .main-sidebar .brand-image {
                float: none;
                margin-left: 0 !important;
                margin-right: .35rem !important;
            }

            .main-sidebar .sidebar {
                padding-left: 0;
                padding-right: 0;
            }

            .main-sidebar .form-inline {
                margin-left: 0;
                margin-right: 0;
            }

            .main-sidebar .nav-sidebar > .nav-item > .nav-link {
                padding-left: .35rem;
                display: flex;
                justify-content: flex-start;
                align-items: center;
            }

            .main-sidebar .nav-sidebar .nav-icon {
                margin-left: 0 !important;
                margin-right: .35rem !important;
            }

            .main-sidebar .nav-sidebar .nav-link p {
                margin-left: 0;
                text-align: left;
            }

            .main-sidebar .nav-treeview > .nav-item > .nav-link {
                padding-left: .35rem !important;
            }

            .main-sidebar .nav-treeview {
                padding-left: 0 !important;
            }

            .main-sidebar .nav-treeview .nav-icon {
                margin-right: .25rem !important;
            }
        </style>
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>

                @each('adminlte::partials.sidebar.menu-item', $searchItems, 'item')

                @if($permittedSubmenus->isNotEmpty())
                    @foreach($permittedSubmenus as $child)
                        @php
                            $childIconRaw = trim((string) ($child->Icono ?? ''));
                            $childIconParts = array_map('trim', explode('|', $childIconRaw, 2));
                            $childIconClass = $childIconParts[0] ?? 'far fa-circle';
                            $childIconColor = $childIconParts[1] ?? '';
                        @endphp

                        <li class="nav-item">
                            <a href="{{ $child->link_with_menu }}" class="nav-link {{ request()->fullUrlIs($child->link_with_menu) ? 'active' : '' }}">
                                <i class="nav-icon {{ $childIconClass }}" @if($childIconColor !== '') style="color: {{ $childIconColor }};" @endif></i>
                                <p>{{ $child->Titulo }}</p>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </nav>
    </div>

</aside>
