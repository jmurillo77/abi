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
                <i class="fas fa-bullhorn text-primary"></i> Gestión de Campañas
            </h1>
        </div>

        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Campañas</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="card card-outline card-primary shadow">

    <div class="card-header">
        <h3 class="card-title">Lista de campañas</h3>
    </div>

    <div class="card-body">
        <table id="campaigns" class="table table-hover table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th width="130">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->IdCampaign }}</td>
                        <td>{{ $campaign->Nombre }}</td>
                        <td>{{ $campaign->TipoCampaign?->Nombre ?? 'Sin tipo' }}</td>
                        <td>{{ $campaign->Estado }}</td>
                        <td class="text-center">
                            <a href="{{ route('contacto.campaign.show', $campaign->IdCampaign) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('contacto.campaign.exporta', $campaign->IdCampaign) }}"
                               class="btn btn-sm btn-success"
                               title="Exportar números">
                                <i class="fas fa-file-export"></i>
                            </a>
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
    $('#campaigns').DataTable({
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
