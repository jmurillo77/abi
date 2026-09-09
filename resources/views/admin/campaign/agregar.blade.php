@extends('adminlte::page')

@section('tituloPagina', 'Crear nueva persona')


@section('contenido')


    <div class="m-3  "></div>
    <div class="card m-3 ">
        <div class="card-header d-flex flex-row">
            <a href="{{ route('persona.index') }}" style="color:#5C636A; margin: 10px 0 0 0px;">
                <span class="fas fa-arrow-left fa-lg"></span></a>
            <div style="margin: 10px 0 0 15px;">
                <h5>Agregar Persona</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    @if ($errors->any())
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <p class="card-text">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('persona.store') }}">
                <!-- Add csrf token -->
                {{ csrf_field() }}
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-id-card fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <input type="text" name="dni" class="form-control form-control-lg"
                        placeholder="Documento de identificación" aria-label="Documento de identificación"
                        aria-describedby="basic-addon1" required>
                        {{-- @error('dni'){{$message}} @enderror --}}
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-user fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <input type="text" name="nombres" class="form-control form-control-lg" placeholder="Nombres"
                        aria-label="Nombres" aria-describedby="basic-addon1" required>
                    <input type="text" name="apellidos" class="form-control form-control-lg" placeholder="Apellidos"
                        aria-label="Apellidos" aria-describedby="basic-addon1" required>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone-alt fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <input type="text" name="numero" class="form-control form-control-lg"
                        placeholder="Número de teléfono" aria-label="Número de teléfono" aria-describedby="basic-addon1"
                        required>
                        {{-- @error('numero'){{$message}} @enderror --}}
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-wifi fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <select name="id_conectividad" class="form-select form-select-lg" aria-label="Tipo de conectividad"
                        aria-describedby="basic-addon1" required>
                        <option hidden selected value="">Conectividad</option>
                        <option value="1">NA</option>
                        <option value="2">Fijo</option>
                        <option value="3">Movil</option>
                    </select>
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-sim-card fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <select name="id_operadora" class="form-select form-select-lg" aria-label="Tipo de operadora"
                        aria-describedby="basic-addon1" required>
                        <option hidden selected value="">Operadora</option>
                        <option value="1">NA</option>
                        <option value="2">Claro</option>
                        <option value="3">Movistar</option>
                        <option value="4">CNT</option>
                    </select>
                </div>
                <br>
                {{-- <a href="{{ route('persona.index') }}" class="btn btn-secondary"><span class="fas fa-undo fa-lg"></span>
                    Regresar</a> --}}
                <button class="btn btn-primary"><span class="fas fa-user-plus fa-lg"></span> Agregar</button>
            </form>

            </p>
        </div>

    </div>

@endsection
