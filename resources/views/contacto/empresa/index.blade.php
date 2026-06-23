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
                <i class="fas fa-building text-primary"></i> Gestión de Empresas
            </h1>
        </div>

        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('menu') }}">Menú</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('contacto.dashboard') }}">Contacto</a>
                </li>
                <li class="breadcrumb-item active">
                    Empresas
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
                <h3 class="card-title">Lista de Empresas</h3>

                <a href="{{ route('contacto.empresa.crear') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Empresa
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="empresas" class="table table-hover table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>RUC</th>
                            <th>Razón Social</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($empresas as $empresa)
                            @php
                                $telefono = optional($empresa->telefono_movils->first())->Numero;
                                $correo = optional($empresa->correos->first())->Correo;
                            @endphp
                            <tr>
                                <td>{{ $empresa->IdEmpresa }}</td>
                                <td>{{ $empresa->RUC }}</td>
                                <td>{{ $empresa->RazonSocial }}</td>
                                <td>
                                    @if($telefono)
                                        {{ $telefono }}
                                    @else
                                        <span class="badge badge-warning">Sin teléfono</span>
                                    @endif
                                </td>
                                <td>
                                    @if($correo)
                                        {{ $correo }}
                                    @else
                                        <span class="badge badge-secondary">Sin correo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('contacto.empresa.show', $empresa->IdEmpresa) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('contacto.empresa.edit', $empresa->IdEmpresa) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('contacto.empresa.destroy', $empresa->IdEmpresa) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta empresa?')" title="Eliminar">
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

    </div>
@endsection

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
    $('#empresas').DataTable({
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
