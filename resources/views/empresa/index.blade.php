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
                    <li class="breadcrumb-item active">Empresa</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')

    <x-adminlte-card title="Lista de Eventos">
        <div class="card-body">
            
        </div>
    </x-adminlte-card>

@endsection
