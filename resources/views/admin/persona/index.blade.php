@extends('adminlte::page')

@section('title', 'AbiSyS')

@section('css')
    <link rel="stylesheet" href="../vendor/datatables/css/dataTables.bootstrap4.min.css">    
    <link rel="stylesheet" href="../vendor/datatables-responsive/css/responsive.bootstrap4.min.css">
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

        <div class="card-body">
            <table id="personas" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 15px">DNI</th>
                        <th style="width: 50px">Nombres</th>
                        <th style="width: 50px">Apellidos</th>
                        <th >Ver</th>
                        <th >Editar</th>
                        <th >Eliminar</th>
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
                            <td width="10px"><a href="persona/{{ $persona->IdPersona }}" class="btn btn-xs btn-teal mx-1 shadow">
                                <i class="fa fa-lg fa-fw fa-eye"></i></a></td>
                            <td width="10px"><a href="persona/{{ $persona->IdPersona }}" class="btn btn-xs btn-primary mx-1 shadow">
                                <i class="fa fa-lg fa-fw fa-pen"></i></a></td>
                            <td width="10px"><a href="persona/{{ $persona->IdPersona }}" class="btn btn-xs btn-danger mx-1 shadow">
                                <i class="fa fa-lg fa-fw fa-trash"></i></a></td>    
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 15px">DNI</th>
                        <th style="width: 50px">Nombres</th>
                        <th style="width: 50px">Apellidos</th>
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
<script>
$(function () {
      $('#personas').DataTable({
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');;
    });
  </script>
@endsection
