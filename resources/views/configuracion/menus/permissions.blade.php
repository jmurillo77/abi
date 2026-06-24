@extends('adminlte::page')

@section('title', 'Permisos de Menús')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Permisos para {{ $user->name }}</h1>
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
        <form action="{{ route('menus.permissions.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Menú</th>
                        <th>Ver</th>
                        <th>Crear</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->Nombre }}</td>
                            @php
                                $pivot = $user->menus->firstWhere('IdMenu', $menu->IdMenu)?->pivot;
                            @endphp
                            <td><input type="checkbox" name="permissions[{{ $menu->IdMenu }}][can_view]" {{ $pivot && $pivot->can_view ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="permissions[{{ $menu->IdMenu }}][can_create]" {{ $pivot && $pivot->can_create ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="permissions[{{ $menu->IdMenu }}][can_edit]" {{ $pivot && $pivot->can_edit ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="permissions[{{ $menu->IdMenu }}][can_delete]" {{ $pivot && $pivot->can_delete ? 'checked' : '' }}></td>
                        </tr>
                        @if($menu->children->count())
                            @foreach($menu->children as $child)
                                @php $pivot = $user->menus->firstWhere('IdMenu', $child->IdMenu)?->pivot; @endphp
                                <tr>
                                    <td class="ps-4">- {{ $child->Nombre }}</td>
                                    <td><input type="checkbox" name="permissions[{{ $child->IdMenu }}][can_view]" {{ $pivot && $pivot->can_view ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="permissions[{{ $child->IdMenu }}][can_create]" {{ $pivot && $pivot->can_create ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="permissions[{{ $child->IdMenu }}][can_edit]" {{ $pivot && $pivot->can_edit ? 'checked' : '' }}></td>
                                    <td><input type="checkbox" name="permissions[{{ $child->IdMenu }}][can_delete]" {{ $pivot && $pivot->can_delete ? 'checked' : '' }}></td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>

            <button class="btn btn-primary">Guardar permisos</button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
