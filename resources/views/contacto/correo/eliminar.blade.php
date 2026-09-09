@extends('layout/plantilla')

@section('tituloPagina', 'Crear nueva persona')

@section('contenido')
    <br>
    <div class="card m-4">
        <h5 class="card-header">Eliminar persona:</h5>
        <div class="card-body">
            <p class="card-text">
            <div class="alert alert-danger" role="alert">
                ¡¡¡¿Seguro de eliminar este registro?!!!
                <table class="table table-sn table-bordered">
                    <thead>
                        <th>Id_persona</th>
                        <th>DNI</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                    </tbody>

                </table>
                <hr>
                <form action="">
                    <a href="{{ route('persona.index') }}" class="btn btn-secondary">
                        <span class="fas fa-undo fa-fw"></span> Regresar</a>
                <button class="btn btn-danger">
                    <span class="fas fa-user-times fa-fw"></span> Eliminar</button>
                </form>

            </div>

            </p>

        </div>
    </div>
@endsection
