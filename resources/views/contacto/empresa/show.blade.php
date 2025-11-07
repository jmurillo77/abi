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
                    <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menu</a> </li>
                    <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contacto - Dashboard</a> </li>
                    <li class="breadcrumb-item"><a href="{{ route('contacto.persona.index') }}">Personas</a></li>
                    <li class="breadcrumb-item active">{{$empresa->first()->DNI}} | {{$empresa->first()->Nombres}} {{$empresa->first()->Apellidos}}</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')

    <x-adminlte-card title="Datos de Persona">
        <div class="card-body">
            <li>DNI: {{$persona->first()->DNI}}</li> 
            <li>Nombres: {{$persona->first()->Nombres}}</li> 
            <li>Apellidos: {{$persona->first()->Apellidos}}</li> 
            <li>Fecha de Nacimiento: {{$persona->first()->FechaNacimiento}}</li>
        </div>
        <table>
            <thead>
                <th>Numeros</th>
            </thead>
            <tbody>
                @foreach($persona->first()->telefono_movils as $telefono)
                <tr>
                    <td>
                        {{$telefono->Numero}}
                        {{$telefono->operadora->Nombre}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>

@endsection
