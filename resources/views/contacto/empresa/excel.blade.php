@extends('layout/plantilla')

@section('tituloPagina', 'Importar Excel')

@section('contenido')

    <section class="section-1">
        <ul>
            <li><a href="#">Enlace</a></li>
            <li><a href="#">Enlace</a></li>
            <li id="save" style="float:right">
                <button onclick="document.forms.save.submit()">
                    <span class="fas fa-cloud-upload-alt"></span>
                    <p>Guardar</p>
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
                        <ul>
                            @foreach ($errors->all() as $error)
                                <p><strong>Uy! Algo salió mal</strong></p>
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
                <div class="row">
                    <form id="save" action="{{ route('persona.excel') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="clo-md-6">
                            <input type="file" name="documento">
                        </div>

                    </form>
                </div>                
            </div>
    </section>



    {{-- <script src=" /js/prueba.js"></script> --}}
    <link rel="stylesheet" href="/css/persona/importar.css">
@endsection
