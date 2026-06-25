@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Usuarios</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
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

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name ?? 'Sin nombre' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->Nombre ?? 'Sin rol' }}</td>
                        <td class="text-nowrap">
                            @can('assign-menu-permissions')
                                <a href="{{ route('users.permissions.edit', $user->id) }}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
