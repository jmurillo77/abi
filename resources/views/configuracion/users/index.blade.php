@extends('adminlte::page')

@section('title', 'Usuarios')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
.btn-nuevo-usuario {
    border-radius: 999px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    transition: all 0.2s ease;
}

.btn-nuevo-usuario:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 16px rgba(13, 110, 253, 0.3);
}
</style>
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Usuarios</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('configuracion.users.create') }}" class="btn btn-primary btn-nuevo-usuario">
                <i class="fas fa-user-plus mr-1"></i> Nuevo Usuario
            </a>
        </div>

        <table id="usuarios-table" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php
                        $nombrePersona = trim((string) (($user->persona->Nombres ?? '').' '.($user->persona->Apellidos ?? '')));
                        $nombreMostrar = $nombrePersona !== ''
                            ? $nombrePersona
                            : ($user->name ?: 'Sin nombre');
                    @endphp
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $nombreMostrar }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge badge-success">Verificado</span>
                            @else
                                <span class="badge badge-warning">Pendiente</span>
                            @endif
                        </td>
                        <td>{{ $user->role->Nombre ?? 'Sin rol' }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('configuracion.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                Ver
                            </a>
                            <a href="{{ route('configuracion.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                            @can('assign-menu-permissions')
                                <a href="{{ route('configuracion.users.permissions.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                    Asignar permisos
                                </a>
                            @endcan
                            <form action="{{ route('configuracion.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(function () {
    $('#usuarios-table').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    });
});
</script>
@stop
