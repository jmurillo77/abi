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
                    <li class="breadcrumb-item"><a href="{{ route('campaign.index') }}">Campañas</a></li>
                    <li class="breadcrumb-item active">{{$campaign->IdCampaign}} | {{$campaign->Nombre}} </li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')

    <x-adminlte-card title="Datos de Persona">
        <div class="card-body">
            <li>Campaña: {{$campaign->IdCampaign}}</li> 
            <li>Nombre: {{$campaign->Nombre}}</li>
            <li>Tipo: {{$campaign->TipoCampaign->Nombre}}</li> 
        </div>
        <table>
            <thead>
                <th>Numeros</th>
            </thead>
            @foreach($campaign_wp as $numeros)
        <tr>
            <td>
                {{$numeros->IdCampaignWP}}
                {{$numeros->IdTelefonoMovil}}
                {{$numeros->Estado}}
                {{$numeros->telefono_movils}}                
            </td>
        </tr>
        @endforeach
            
        

            
        </table>
    </x-adminlte-card>

@endsection
