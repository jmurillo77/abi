@extends('layout/plantilla')

@section('tituloPagina', 'Actualiza')

@section('contenido')

    <section class="section-1">
        <ul>
            <li><a href="#">Enlace</a></li>
            <li><a href="#">Enlace</a></li>
            <li id="save" style="float:right">
                <button onclick="document.forms.addTelfsec.submit()">
                    <span class="fas fa-cloud-upload-alt"></span>
                    <p>Guardar</p>
                </button>
            </li>
            <li id="update" style="float:right">
                <button onclick="document.forms.person.submit()">
                    <span class="fas fa-user-edit"></span>
                    <p>Actualizar</p>
                </button>
            </li>
        </ul>
    </section>

    <section class="alert">
        <div class="row">
            <div class="col-sm-12">
                @if ($mensaje = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        {{ $mensaje }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p><strong>Uy! Algo salió mal</strong></p>
                        <br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section-2">
        <div class="card">
            <div class="card-body">
                <form id="person" action="{{ route('persona.update', $persona_telefono->id_persona_telefono) }}"
                    method="POST">
                    <!-- Add csrf token -->
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-user "
                                style="color: #003A66;"></i></span>
                        <input type="text" name="nombres" class="form-control form-control-lg" placeholder="Nombres"
                            aria-label="Nombres" aria-describedby="basic-addon1" required value="{{ $persona->Nombres }}">
                        <input type="text" name="apellidos" class="form-control form-control-lg" placeholder="Apellidos"
                            aria-label="Apellidos" aria-describedby="basic-addon1" required
                            value="{{ $persona->Apellidos }}">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-id-card "
                                style="color: #003A66;"></i></span>
                        <input type="text" name="dni" class="form-control form-control-lg" readonly 
                            placeholder="Documento de identificación" aria-label="Documento de identificación"
                            aria-describedby="basic-addon1" required value="{{ $persona->DNI }}">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone-square-alt "
                                style="color: #003A66;"></i></span>
                        <input type="text" name="numero" class="form-control form-control-lg"
                            placeholder="Número de teléfono" aria-label="Número de teléfono" aria-describedby="basic-addon1"
                            required value="{{ $telefono->Numero }}">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-wifi "
                                style="color: #003A66;"></i></span>
                        <select name="id_conectividad" class="form-select form-select-lg" aria-label="Tipo de conectividad"
                            aria-describedby="basic-addon1" required value="{{ $telefono->id_Conectividad }}">
                            <option value="1" @if ($telefono->id_Conectividad == '1') hidden selected @endif>NA
                            </option>
                            <option value="2" @if ($telefono->id_Conectividad == '2') hidden selected @endif>Fijo
                            </option>
                            <option value="3" @if ($telefono->id_Conectividad == '3') hidden selected @endif>
                                Movil</option>
                        </select>
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-sim-card "
                                style="color: #003A66;"></i></span>
                        <select name="id_operadora" class="form-select form-select-lg" aria-label="Tipo de operadora"
                            aria-describedby="basic-addon1" required value="{{ $telefono->id_Operadora }}">
                            <option value="1" @if ($telefono->id_Operadora == '1') hidden selected @endif>NA
                            </option>
                            <option value="2" @if ($telefono->id_Operadora == '2') hidden selected @endif>
                                Claro</option>
                            <option value="3" @if ($telefono->id_Operadora == '3') hidden selected @endif>
                                Movistar
                            </option>
                            <option value="4" @if ($telefono->id_Operadora == '4') hidden selected @endif>CNT
                            </option>
                        </select>
                    </div>
                </form>
            </div>
    </section>

    <section class="section-3">
        <div class="card">
            <ul class="horizontal">
                <li class="item-1">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false"
                                    aria-controls="flush-collapseOne">
                                    Telefonos secundarios:
                                </button>
                            </h2>

                            <div id="flush-collapseOne" class="accordion-collapse collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    @foreach ($pt_sec as $item)
                                        @if ($item->id_persona === $persona_telefono->id_persona)
                                            @foreach ($telf as $valor)
                                                @if ($item->id_telefono_secundarios === $valor->id_telefono)
                                                    <form id="telfsec">
                                                        {{ csrf_field() }}
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-phone-square-alt "
                                                                    style="color: #003A66;"></i></span>
                                                            <input type="text" name="num"
                                                                class="form-control form-control-lg"
                                                                placeholder="Número de teléfono"
                                                                aria-label="Número de teléfono"
                                                                aria-describedby="basic-addon1" required
                                                                value="{{ $valor->Numero }}">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-wifi "
                                                                    style="color: #003A66;"></i></span>
                                                            <select name="id_con" class="form-select form-select-lg"
                                                                aria-label="Tipo de conectividad"
                                                                aria-describedby="basic-addon1" required
                                                                value="{{ $valor->id_Conectividad }}">
                                                                <option value="1"
                                                                    @if ($valor->id_Conectividad == '1') hidden selected @endif>
                                                                    NA
                                                                </option>
                                                                <option value="2"
                                                                    @if ($valor->id_Conectividad == '2') hidden selected @endif>
                                                                    Fijo
                                                                </option>
                                                                <option value="3"
                                                                    @if ($valor->id_Conectividad == '3') hidden selected @endif>
                                                                    Movil</option>
                                                            </select>
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-sim-card "
                                                                    style="color: #003A66;"></i></span>
                                                            <select name="id_op" class="form-select form-select-lg"
                                                                aria-label="Tipo de operadora"
                                                                aria-describedby="basic-addon1" required
                                                                value="{{ $valor->id_Operadora }}">
                                                                <option value="1"
                                                                    @if ($valor->id_Operadora == '1') hidden selected @endif>
                                                                    NA
                                                                </option>
                                                                <option value="2"
                                                                    @if ($valor->id_Operadora == '2') hidden selected @endif>
                                                                    Claro</option>
                                                                <option value="3"
                                                                    @if ($valor->id_Operadora == '3') hidden selected @endif>
                                                                    Movistar
                                                                </option>
                                                                <option value="4"
                                                                    @if ($valor->id_Operadora == '4') hidden selected @endif>
                                                                    CNT
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </form>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="item-2" style="float:right">
                    <button id="btnAdd" type="button" class="btn btn-tool">
                        <i class="fas fa-plus" style="color: #003A66;"></i>
                    </button>
                </li>

            </ul>
        </div>
        <div class="container">
            <form id="addTelfsec" role="form" method="POST" action="{{ route('persona.telfSecundarios') }}">
                {{ csrf_field() }}
                <div id="addElement"></div>
            </form>
        </div>

        <div class="row">
            <div class="col-sm-12">
                @if ($mensaje = Session::get('success'))
                    <div class="alert alert-success" role="alert">
                        {{ $mensaje }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- <script src=" /js/prueba.js"></script> --}}
    <script src=" /js/project.js"></script>
    <script src="/js/dom.js"></script>
    <link rel="stylesheet" href="/css/persona/actualizar.css">
@endsection
