@extends('plantilla.general')

@section('tituloPagina', 'Lista de pedidos')

@section('estilos')

    <!-- CSS de Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- JavaScript de Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="/css/listaPedidos.css">
    <!-- JavaScript de DataTables -->
    <script type="text/javascript" src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

@endsection

@section('contenido')
    <div class="regresar">
        <a href="{{ route('dashboard') }}"><img src={{ asset('img/flecha.png') }} alt=""></a>
    </div>
    <div class="imagen">
        <img src={{ asset('img/abicourier.png') }} alt="">
    </div>
    <div class="section-table p-3">
        <div class="table-responsive-lg">
            <table id="tablaListaPedidos" class="table align-middle table-hover display">
                <thead class="table-secondary">
                    <tr>
                        <th></th>
                        @foreach ($heads as $row_head)
                            <th scope="col" class="data">{!! $row_head !!}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($config['data'] as $row)
                        <tr onclick="redirect('{{ route('pedido.pedido', $row[0]) }}')">
                            <td class="_check"><input class="form-check-input mt-0" type="checkbox" value=""
                                    aria-label="Checkbox for following text input"></td>
                            @foreach ($row as $cell)
                                <td class="data">{!! $cell !!}</td>
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let tables = $('#tablaListaPedidos').DataTable({
                "pageLength": 15,
                "lengthMenu": [
                    [15, 25, 50, 100, -1],
                    [15, 25, 50, 100, "All"]
                ],
                "order": [[0, "desc"]]
            });

        });

        function redirect(url) {
            window.location.href = url;
        }
    </script>
@endsection
