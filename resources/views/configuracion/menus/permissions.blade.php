@extends('adminlte::page')

@section('title', 'Permisos de Menús')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Permisos para {{ $user->name ?? $user->email }}</h1>
            <p class="text-muted mb-0">Rol actual: {{ $user->role->Nombre ?? 'Sin rol' }}</p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menús</a></li>
                <li class="breadcrumb-item active">Permisos</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            Los permisos se asignan al rol del usuario mediante la tabla permisos_submenu_rol.
        </div>

        <form action="{{ route('users.permissions.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Menú / Submenú</th>
                        <th>Acceso</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        @if($menu->children->count())
                            <tr class="table-secondary">
                                <td colspan="2"><strong>{{ $menu->Titulo }}</strong></td>
                            </tr>
                            @foreach($menu->children as $child)
                                <tr>
                                    <td class="ps-4">- {{ $child->Titulo }}</td>
                                    <td>
                                        <input type="checkbox" name="permissions[]" value="{{ $child->IdSubMenu }}" {{ in_array($child->IdSubMenu, $permittedSubmenuIds ?? []) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>{{ $menu->Titulo }}</td>
                                <td><span class="text-muted">Sin submenús configurados</span></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <button class="btn btn-primary">Guardar permisos</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
