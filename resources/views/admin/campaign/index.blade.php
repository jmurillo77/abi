@extends('adminlte::page')

@section('title', 'AbiSyS')

@section('css')
    <link rel="stylesheet" href="../vendor/datatables/css/dataTables.bootstrap4.min.css">    
    <link rel="stylesheet" href="../vendor/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css">
@endsection

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a> </li>
                    <li class="breadcrumb-item active">Campañas</li>
                </ol>
            </div>
        </div>
    </div>
@stop



@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Campañas</h3>
        </div>

        <div class="card-body">
            <table id="empresas" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 15px">Nombre</th>
                        <th style="width: 15px">Tipo</th>
                        <th style="width: 15px">Estado</th>
                        <th >Ver</th>
                        <th >Editar</th>
                        <th >Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaigns as $campaign)
                        <tr>
                            <td style="vertical-align: middle"><a href="campaign/{{ $campaign->IdCampaign }}">{{ $campaign->IdCampaign }}</a></td>
                            <td style="vertical-align: middle;">{{ $campaign->Nombre }}</td>
                            <td style="vertical-align: middle;">{{ $campaign->TipoCampaign()->Nombre }}</td>
                            <td style="vertical-align: middle;">{{ $campaign->Estado }}</td>
                            <td width="10px"><a href="campaign/{{ $campaign->IdCampaign }}" class="btn btn-xs btn-teal mx-1 shadow">
                                <i class="fa fa-lg fa-fw fa-eye"></i></a></td>
                            <td width="10px"><a href="campaign/{{ $campaign->IdCampaign }}" class="btn btn-xs btn-primary mx-1 shadow">
                                <i class="fa fa-lg fa-fw fa-pen"></i></a></td>
                            <td width="10px"><a href="campaign/{{ $campaign->IdCampaign }}" class="btn btn-xs btn-danger mx-1 shadow">
                                <i class="fa fa-lg fa-fw fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 50px">Nombre</th>
                        <th style="width: 15px">Tipo</th>
                        <th style="width: 15px">Estado</th>
                        <th >Ver</th>
                        <th >Editar</th>
                        <th >Eliminar</th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
@endsection

@section('js')
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="../vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../vendor/datatables-plugins/jszip/jszip.min.js"></script>
    <script src="../vendor/datatables-plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../vendor/datatables-plugins/buttons/js/buttons.html5.min.js"></script>
    <script>
        $('#empresas').DataTable({
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    </script>
@endsection
