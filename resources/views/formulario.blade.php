@extends('plantilla.general')

@section('tituloPagina', 'Nuevo pedido')

@section('estilos')
    <style>
        body {
            background-color: #fff;
        }

        .regresar,
        .imagen {
            display: flex;
        }

        .regresar {
            position: absolute;
            align-items: start;
            justify-content: start;
            max-width: 3rem;
            margin-top: 20px;
            margin-left: 20px;
        }

        .regresar img {
            max-width: 100%;
            max-height: 100%;
        }

        .imagen {
            align-items: center;
            justify-content: center;
            height: 10rem;
        }

        .imagen img {
            max-width: 100%;
            max-height: 100%;
        }

        .card {
            max-width: 700px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .form-label {
            color: #003986;
            font-weight: 500;
            font-size: 14px;
        }

        .row {
            bottom: 0;
            display: flex;
            align-items: flex-end;
        }

        .alineacion_error {
            align-items: flex-start;
        }

        #section-USA {
            background-color: red;
            color: #fff;
            border-radius: 10px;
        }

        #section-AbiCourier {
            background-color: #F1B41F;
            color: #fff;
            border-radius: 10px;
        }

        #section-IMG {
            background-color: blueviolet;
            color: #fff;
            border-radius: 10px;
        }

        #section-USA .form-label,
        #section-AbiCourier .form-label,
        #section-IMG .form-label {
            color: #ffffff;
        }

        span {
            font-size: x-large;
            font-weight: bold;
        }

        .error-message {
            display: flex;
            align-items: center;
            color: #f50000;
            font-size: 14px;
            font-weight: bold;
            font-style: normal;
            margin-bottom: 20px;
        }

        .fechas {
            bottom: 0;
            width: 100%;
            display: contents;
            align-items: flex-end;
        }

        .fechas .col {
            margin-left: 15px;
        }

        .btn-primary {
            min-width: 150px;
            max-width: 200px;
            margin-left: auto;
            margin-right: auto;
        }

        @media screen and (max-width: 991px) {
            .card {
                max-width: 100%;
                margin: 20px;
            }


        }
    </style>

    <!-- CSS de Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- JavaScript de Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@endsection

@section('contenido')
    <div class="regresar">
        <a href="{{ route('dashboard') }}"><img src={{ asset('img/flecha.png') }} alt=""></a>
    </div>
    <div class="imagen">
        <img src={{ asset('img/abicourier.png') }} alt="">
    </div>
    <div class="card">
        <div class="card-body">
            <form id="guardarPedido" action="{{ route('listaPedido.guardarPedido') }}" class="form-horizontal" role="form"
                method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="mb-3">
                    <label for="InputCiudad" class="form-label">Ciudad</label>
                    <input type="text" class="form-control" id="InputCiudad" name="ciudad" value="{{ old('ciudad') }}">
                </div>
                <div class="row g-3" id="section-Cliente">
                    <div class="col-md-3 mb-3">
                        <label for="InputCedula" class="form-label">Documento de identificación:</label>
                        <input type="text" class="form-control" id="InputCedula" placeholder="" name="cedula"
                            value="{{ old('cedula') }}">
                    </div>
                    <div class="col-md-9 mb-3">
                        <label for="InputNombres" class="form-label">Nombres del cliente:</label>
                        <input type="text" class="form-control " id="InputNombres" placeholder="" name="nombres"
                            value="{{ old('nombres') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="InputEmail" class="form-label">Correo electronico:</label>
                    <input type="text" class="form-control" id="InputEmail" name="email" value="{{ old('email') }}">
                </div>
                <div class="row g-3" id="section-EstadoCompra">
                    <div class="col mb-3">
                        <label for="InputCourier" class="form-label">Cliente de:</label>
                        <select class="form-select" aria-label="Default select example" id="InputCourier" name="courier"
                            value="{{ old('courier') }}">
                            <option value="" selected></option>
                            <option value="AbiCourier">Abi Courier</option>
                            <option value="MoveCourier">Move Courier</option>
                        </select>
                    </div>
                    <div class="col mb-3">
                        <label for="InputEstado" class="form-label">Estado de la compra:</label>
                        <select class="form-select" aria-label="Default select example" id="InputEstado" name="estado"
                            value="{{ old('estado') }}">
                            <option value="" selected></option>
                            <option value="Pagado">Pagado</option>
                            <option value="NoPagado">No pagado</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3" id="section-PaqueteTarifa">
                    <div class="col mb-3">
                        <label for="InputPeso" class="form-label">Peso del paquete (lb):</label>
                        <input type="text" class="form-control" id="InputPeso" placeholder="" name="peso"
                            value="{{ old('peso') }}">
                    </div>
                    <div class="col mb-3 ">
                        <label for="InputTarifa" class="form-label">Tarifa del paquete:</label>
                        <select class="form-select" aria-label="Default select example" id="InputTarifa" name="tarifa"
                            value="{{ old('tarifa') }}">
                            <option selected></option>
                            <option value="5.50">$5.50 Normal</option>
                            <option value="8">$8 Express</option>
                            <option value="7">$7 Bitscopy</option>
                            <option value="10">$10 Premium</option>
                        </select>
                    </div>
                    <div class="col mb-3 ">
                        <label for="InputTotal" class="form-label">Total (Peso-Tarifa):</label>
                        <input class="form-control" type="text" placeholder="Peso-Tarifa"
                            aria-label="Disabled input example" id="InputTotal" name="pesoTarifa_" disabled
                            value="{{ old('pesoTarifa_') }}">
                        <input type="hidden" id="pesoTarifaHidden" name="pesoTarifa" value="{{ old('pesoTarifa') }}">
                    </div>
                </div>
                <div class="row g-3 p-3 mb-3 mt-3" id="section-USA">
                    <div class="mb-3 d-flex justify-content-center">
                        <span style="text-align: center;">USA</span>
                    </div>
                    <div class="fechas">
                        <div class="col mb-3">
                            <label for="USAfechaCompra" class="form-label">Fecha de compra:</label>
                            <input type="text" class="form-control" id="USAfechaCompra" name="USAfechaCompra"
                                placeholder="DD-MM-YYYY" value="{{ old('USAfechaCompra') }}">
                        </div>
                        <div class="col mb-3 ">
                            <label for="USAfechaEstimadaLlegada" class="form-label">Fecha estimada de llegada:</label>
                            <input type="text" class="form-control" id="USAfechaEstimadaLlegada"
                                name="USAfechaEstimadaLlegada" placeholder="DD-MM-YYYY"
                                value="{{ old('USAfechaEstimadaLlegada') }}">
                        </div>
                        <div class="col mb-3">
                            <label for="USAfechaLlegada" class="form-label">Fecha de llegada:</label>
                            <input type="text" class="form-control" id="USAfechaLlegada" name="USAfechaLlegada"
                                placeholder="DD-MM-YYYY" value="{{ old('USAfechaLlegada') }}">
                        </div>
                        <div class="col mb-3">
                            <label for="USAfechaEnvio" class="form-label">Fecha de envio:</label>
                            <input type="text" class="form-control" id="USAfechaEnvio" name="USAfechaEnvio"
                                placeholder="DD-MM-YYYY" value="{{ old('USAfechaEnvio') }}">
                        </div>
                    </div>
                </div>
                <div class="row g-3 p-3 mb-3 mt-3" id="section-AbiCourier">
                    <div class="mb-3 d-flex justify-content-center">
                        <span style="text-align: center;">Abi Courier</span>
                    </div>
                    <div class="fechas">
                        <div class="col mb-3">
                            <label for="ABIfechaEstimadaLlegada" class="form-label">Fecha estimada de llegada:</label>
                            <input type="text" class="form-control" id="ABIfechaEstimadaLlegada"
                                name="ABIfechaEstimadaLlegada" placeholder="DD-MM-YYYY"
                                value="{{ old('ABIfechaEstimadaLlegada') }}">
                        </div>
                        <div class="col mb-3">
                            <label for="ABIfechaLlegada" class="form-label">Fecha de llegada:</label>
                            <input type="text" class="form-control" id="ABIfechaLlegada" name="ABIfechaLlegada"
                                placeholder="DD-MM-YYYY" value="{{ old('ABIfechaLlegada') }}">
                        </div>
                        <div class="col mb-3">
                            <label for="ABIfechaEntrega" class="form-label">Fecha de entrega:</label>
                            <input type="text" class="form-control" id="ABIfechaEntrega" name="ABIfechaEntrega"
                                placeholder="DD-MM-YYYY" value="{{ old('ABIfechaEntrega') }}">
                        </div>
                    </div>
                </div>
                <div class="row g-3 p-3 mb-3 mt-3" id="section-IMG">
                    <div class="mb-3">
                        <label for="ImagenPedido" class="form-label">Imagen de pedido:</label>
                        <input class="form-control form-control-sm" id="ImagenPedido" type="file" name="ImagenPedido"
                            value="{{ old('ImagenPedido') }}">
                    </div>
                    <div class="mb-3">
                        <label for="ImagenPaquete" class="form-label">Imagen de paquete:</label>
                        <input class="form-control form-control-sm" id="ImagenPaquete" type="file"
                            name="ImagenPaquete" value="{{ old('ImagenPaquete') }}">
                    </div>
                    <div class="mb-3">
                        <label for="ImagenPago" class="form-label">Imagen de pago:</label>
                        <input class="form-control form-control-sm" id="ImagenPago" type="file" name="ImagenPago"
                            value="{{ old('ImagenPago') }}">
                    </div>
                </div>
                <div class="row pt-3">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('/js/formulario.js') }}"></script>
@endsection
