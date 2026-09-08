@extends('adminlte::page')

@section('title', 'Editar Pedido')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-edit text-primary"></i> Editar Pedido #{{ $pedido->id }}
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.pedido.index') }}">Pedidos</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card card-outline card-primary shadow">
            <div class="card-header">
                <h3 class="card-title">Actualizar Pedido</h3>
            </div>

            <form action="{{ route('ventas.pedido.update', $pedido->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Se encontraron errores:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="IdCliente">Cliente <span class="text-danger">*</span></label>
                        <select id="IdCliente" name="IdCliente" class="form-control" required>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('IdCliente', $pedido->IdCliente) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->NombreRelacionado }} @if($cliente->Documento) ({{ $cliente->Documento }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="IdDireccion">Dirección de envío</label>
                        <select id="IdDireccion" name="IdDireccion" class="form-control">
                            <option value="">Sin dirección</option>
                            @foreach($direcciones as $index => $direccion)
                                <option value="{{ $direccion->IdDireccion }}" {{ old('IdDireccion', $pedido->IdDireccion) == $direccion->IdDireccion ? 'selected' : '' }}>
                                    {{ $direccion->Etiqueta }}{{ $index === 0 ? ' (Principal)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @if($pedido->direccion?->GoogleMapsUrl)
                            <p class="small mt-1 mb-0">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <a href="{{ $pedido->direccion->GoogleMapsUrl }}" target="_blank" rel="noopener">Ver ubicación en el mapa</a>
                            </p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="IdRuta">Recorrido</label>
                        <select id="IdRuta" name="IdRuta" class="form-control">
                            <option value="">Sin recorrido asignado</option>
                            @foreach($rutas as $ruta)
                                <option value="{{ $ruta->id }}" {{ (string) old('IdRuta', $pedido->IdRuta) === (string) $ruta->id ? 'selected' : '' }}>
                                    {{ $ruta->Nombre }} — {{ optional($ruta->Fecha)->format('d/m/Y') }} ({{ $ruta->EstadoLabel }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Estado">Estado <span class="text-danger">*</span></label>
                        <select id="Estado" name="Estado" class="form-control" required>
                            @foreach(['PENDIENTE' => 'Pendiente', 'EN_PREPARACION' => 'En preparación', 'ENTREGADO' => 'Entregado', 'CANCELADO' => 'Cancelado'] as $value => $label)
                                <option value="{{ $value }}" {{ old('Estado', $pedido->Estado) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="Observaciones">Observaciones</label>
                        <textarea id="Observaciones" name="Observaciones" class="form-control" rows="3" maxlength="500">{{ old('Observaciones', $pedido->Observaciones) }}</textarea>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('ventas.pedido.show', $pedido->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card card-outline card-info shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> Productos del pedido</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedido->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->Nombre }}</td>
                                <td><span class="badge badge-light">{{ $detalle->TipoItemLabel }}</span></td>
                                <td>{{ $detalle->Cantidad }}</td>
                                <td class="text-right">${{ number_format((float) $detalle->Subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Este pedido no tiene productos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted small">
                <i class="fas fa-info-circle"></i> Los productos de un pedido no se pueden modificar una vez registrado. Si necesita cambiar los productos, cancele este pedido y registre uno nuevo.
            </div>
        </div>
    </div>
</div>
@stop
