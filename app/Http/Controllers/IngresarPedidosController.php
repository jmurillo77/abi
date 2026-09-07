<?php

namespace App\Http\Controllers;

use App\Http\Requests\guardarPedido;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IngresarPedidosController extends Controller
{
    public function formulario(Request $request)
    {
        return view('formulario');
    }

    public function listaPedidos(Request $request)
    {
        $clientes = DB::table('clientes')->get();

        $heads = [
            'Id',
            'Cedula',
            'Cliente',
            'Ciudad',
            'Cliente de:',
            'Estado de la compra',
            'Peso (lb)',
            'Tarifa por libra ($)',
            'Total (Peso Tarifa)',
            'Fecha de compra (USA)',
            'Fecha estimada de llegada (USA)',
            'Fecha de llegada (USA)',
            'Fecha de envio (USA)',
            'Fecha estimada de llegada (EC)',
            'Fecha de llegada (EC)',
            'Fecha de Entrega cliente',
            'Imagen pedido',
            'Imagen paquete',
            'Imagen pago',
        ];


        $data = [];

        foreach ($clientes as $cliente) {
            // Construir un array con los datos relevantes del cliente
            $data[] = [
                $cliente->IdClientes,
                $cliente->cedula,
                $cliente->nombres,
                $cliente->ciudad,
                $cliente->courier,
                $cliente->estado,
                $cliente->peso,
                $cliente->tarifa,
                $cliente->peso_tarifa,
                $cliente->USA_fecha_compra,
                $cliente->USA_fecha_estimada_llegada,
                $cliente->USA_fecha_llegada,
                $cliente->USA_fecha_envio,
                $cliente->ABI_fecha_estimada_llegada,
                $cliente->ABI_fecha_llegada,
                $cliente->ABI_fecha_entrega,
                $cliente->imagen_pedido,
                $cliente->imagen_paquete,
                $cliente->imagen_pago,
            ];
        }


        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [
                ['orderable' => false], null, null, null, null, null, null, null, null, null, null, null, null, null, null, null
            ]
        ];
        

        return view('listaPedidos', compact('config', 'heads'));
    }

    public function guardarPedido(guardarPedido $request)
    {
        $ciudad =  $request->input('ciudad') ?? '';
        $cedula =  $request->input('cedula') ?? '';
        $nombres =  $request->input('nombres') ?? '';
        $email =  $request->input('email') ?? '';
        $courier =  $request->input('courier') ?? '';
        $estado =  $request->input('estado') ?? '';
        $peso =  $request->input('peso') ?? '';
        $tarifa =  $request->input('tarifa') ?? '';
        $pesoTarifa =  $request->input('pesoTarifa') ?? '';
        $USAfechaCompra =  $request->input('USAfechaCompra') ?? '';
        $USAfechaEstimadaLlegada =  $request->input('USAfechaEstimadaLlegada') ?? '';
        $USAfechaLlegada =  $request->input('USAfechaLlegada') ?? '';
        $USAfechaEnvio =  $request->input('USAfechaEnvio') ?? '';
        $ABIfechaEstimadaLlegada =  $request->input('ABIfechaEstimadaLlegada') ?? '';
        $ABIfechaLlegada =  $request->input('ABIfechaLlegada') ?? '';
        $ABIfechaEntrega =  $request->input('ABIfechaEntrega') ?? '';
        $imagenPedido = $request->file('ImagenPedido') ?? null;
        $imagenPaquete = $request->file('ImagenPaquete') ?? null;
        $imagenPago = $request->file('ImagenPago') ?? null;

        $CrearPedido = new Cliente();
        $CrearPedido->ciudad = $ciudad;
        $CrearPedido->cedula = $cedula;
        $CrearPedido->nombres = $nombres;
        $CrearPedido->email = $email;
        $CrearPedido->courier = $courier;
        $CrearPedido->estado = $estado;
        $CrearPedido->peso = $peso;
        $CrearPedido->tarifa = $tarifa;
        $CrearPedido->peso_tarifa = $pesoTarifa;
        $CrearPedido->USA_fecha_compra = $USAfechaCompra;
        $CrearPedido->USA_fecha_estimada_llegada = $USAfechaEstimadaLlegada;
        $CrearPedido->USA_fecha_llegada = $USAfechaLlegada;
        $CrearPedido->USA_fecha_envio = $USAfechaEnvio;
        $CrearPedido->ABI_fecha_estimada_llegada = $ABIfechaEstimadaLlegada;
        $CrearPedido->ABI_fecha_llegada = $ABIfechaLlegada;
        $CrearPedido->ABI_fecha_entrega = $ABIfechaEntrega;
        $CrearPedido->CFecha = now();
        $CrearPedido->save();


        if ($CrearPedido->save()) {

            if ($imagenPedido) {
                $nombreImagenPedido = $this->subirImagen($CrearPedido->IdClientes, $imagenPedido, 'pedido', $CrearPedido->cedula);
                $CrearPedido->imagen_pedido = $nombreImagenPedido;
            }

            if ($imagenPaquete) {
                $nombreImagenPaquete = $this->subirImagen($CrearPedido->IdClientes, $imagenPaquete, 'paquete', $CrearPedido->cedula);
                $CrearPedido->imagen_paquete = $nombreImagenPaquete;
            }

            if ($imagenPago) {
                $nombreImagenPago = $this->subirImagen($CrearPedido->IdClientes, $imagenPago, 'pago', $CrearPedido->cedula);
                $CrearPedido->imagen_pago = $nombreImagenPago;
            }

            if ($CrearPedido->save()) {
                return response()->json(['success' => 'Pedido guardado correctamente']);
            } else {
                return response()->json(['errors' => 'Error al guardar las imágenes'], 500);
            }
        } else {
            return response()->json(['errors' => 'Error al guardar el pedido'], 500);
        }
    }

    function subirImagen($id, $imagen, $tipo, $cedula)
    {
        if ($imagen) {
            $file_name = $imagen->getClientOriginalName();

            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $nombre_personalizado = $id . '_' . $cedula . '_' . $tipo . '.' . $extension;

            Storage::putFileAs('public/imagenes_abicourier', $imagen, $nombre_personalizado);

            return $nombre_personalizado;
        } else {
            return '';
        }
    }
}
