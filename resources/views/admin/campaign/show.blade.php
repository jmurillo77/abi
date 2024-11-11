@extends('adminlte::page')

@section('title', 'AbiSyS')

@section('css')
    <link rel="stylesheet" href="../../vendor/datatables/css/dataTables.bootstrap4.min.css">    
    <link rel="stylesheet" href="../../vendor/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css">
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
                    <li class="breadcrumb-item"><a href="{{ route('campaign.index') }}">Campañas</a></li>
                    <li class="breadcrumb-item active">{{$campaign->IdCampaign}} | {{$campaign->Nombre}} </li>
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
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                <label>Campaña:</label>
                <input type="text" class="form-control" placeholder="{{$campaign->IdCampaign}}" disabled>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <label>Nombre:</label>
                <input type="text" class="form-control" placeholder="{{$campaign->Nombre}}" disabled>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                <label>Tipo:</label>
                <input type="text" class="form-control" placeholder="{{$campaign->TipoCampaign->Nombre}}" disabled>
                </div>
            </div>
        </div>
        <table id="empresas" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th style="width: 100px">Numero</th>
                    <th style="width: 15px">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaign_wp as $numeros)
                    <tr>
                        <td>{{$numeros->IdCampaignWP}}</td>
                        @foreach ( $numeros->telefono_movils as $Num)
                            <td>{{ $Num->Numero }}</td>
                        @endforeach 
                        <td>{{$numeros->Status}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="width: 10px">#</th>
                    <th style="width: 100px">Numero</th>
                    <th style="width: 15px">Estado</th>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection

@section('js')
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js"></script>
    <script src="../../vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../../vendor/datatables-plugins/jszip/jszip.min.js"></script>
    <script src="../../vendor/datatables-plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../../vendor/datatables-plugins/buttons/js/buttons.html5.min.js"></script>
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
            "fixedHeader": true,
        });
    </script>
@endsection