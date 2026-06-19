@extends('adminlte::page')

@section('title', 'AbiSyS')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css') }}">
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-users text-primary"></i> Gestión de Personas
            </h1>
        </div>

        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('menu') }}">Menú</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('contacto.dashboard') }}">Contactos</a>
                </li>
                <li class="breadcrumb-item active">
                    Personas
                </li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="card card-outline card-primary shadow">

    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                Lista de Personas
            </h3>

            <a href="{{ route('contacto.persona.crear') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Nueva Persona
            </a>
        </div>
    </div>

    <div class="card-body">

        <table id="personas" class="table table-hover table-bordered table-striped">

            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th width="150">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($personas as $persona)

                    @php
                        $telefono = optional($persona->telefono_movils->first())->Numero;
                        $correo = optional($persona->correos->first())->Correo;
                    @endphp

                    <tr>
                        <td>{{ $persona->IdPersona }}</td>
                        <td>{{ $persona->DNI }}</td>
                        <td>{{ $persona->Nombres }}</td>
                        <td>{{ $persona->Apellidos }}</td>

                        <td>
                            @if($telefono)
                                {{ $telefono }}
                            @else
                                <span class="badge badge-warning">
                                    Sin teléfono
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($correo)
                                {{ $correo }}
                            @else
                                <span class="badge badge-secondary">
                                    Sin correo
                                </span>
                            @endif
                        </td>

                        <td class="text-center">

                            <a href="{{ route('contacto.persona.show', $persona->IdPersona) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('contacto.persona.edit', $persona->IdPersona) }}"
                               class="btn btn-sm btn-primary"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('contacto.persona.destroy', $persona->IdPersona) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar este registro?')"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>

                @endforeach
            </tbody>

        </table>

    </div>
</div>

@stop

@section('js')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/buttons.html5.min.js') }}"></script>

<script>
$(function () {
    $('#personas').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>
@stop