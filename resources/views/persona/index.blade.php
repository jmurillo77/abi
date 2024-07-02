@extends('adminlte::page')

@section('title', 'AbiSyS')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a> </li>
                    <li class="breadcrumb-item active">Personas</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Personas</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 15px">DNI</th>
                        <th style="width: 50px">Nombres</th>
                        <th style="width: 50px">Apellidos</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($personas as $persona)
                        <tr>
                            <td style="vertical-align: middle"><a
                                    href="persona/{{ $persona->IdPersona }}">{{ $persona->IdPersona }}</a></td>
                            <td style="vertical-align: middle;"><a
                                    href="persona/{{ $persona->IdPersona }}">{{ $persona->DNI }}</a></td>
                            <td style="vertical-align: middle;">{{ $persona->Nombres }}</td>
                            <td style="vertical-align: middle;">{{ $persona->Apellidos }}</td>
                            <td width="10px"><a href="persona/{{ $persona->IdPersona }}" class="btn btn-xs btn-primary"><i class="fa fa-edit fa-fw"></i>Ver</a></td>
                            <td width="10px"><a href="persona/{{ $persona->IdPersona }}" class="btn btn-xs btn-danger"><i class="fa fa-trash fa-fw"></i>Eliminar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
