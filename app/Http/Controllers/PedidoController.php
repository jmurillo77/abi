<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\editarPedido;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function Pedido($id)
    {
        $pedido = Cliente::findOrFail($id);
        return view('pedido', ['pedido' => $pedido]);
    }

    public function PedidoImagenes($idImagen)
    {
        $cliente = Cliente::where('IdClientes', $idImagen)->value('nombres');

        $imagenes = [];

        $imagen_pedido = Cliente::where('IdClientes', $idImagen)->value('imagen_pedido');
        $imagen_paquete = Cliente::where('IdClientes', $idImagen)->value('imagen_paquete');
        $imagen_pago = Cliente::where('IdClientes', $idImagen)->value('imagen_pago');

        if (!$imagen_pedido && !$imagen_paquete && !$imagen_pago) {
            return response()->json(['errors' => 'No se encontraron las imágenes del pedido, del paquete o del pago asociadas a este cliente.'], 500);
        }

        $imagenes = [
            'pedido' =>  $imagen_pedido,
            'paquete' =>  $imagen_paquete,
            'pago' =>  $imagen_pago
        ];

        return redirect()->back()->with(['cliente' => $cliente, 'imagenes' => $imagenes]);
    }

    public function PedidoEdit(editarPedido $request)
    {
        $idPedido_edit = $request->idPedido_edit;

        $dato = Cliente::where('IdClientes', $idPedido_edit)->first();

        $fieldNames = [
            "IdClientes" => "ID de Cliente",
            "ciudad" => "Ciudad",
            "cedula" => "Cédula",
            "nombres" => "Nombres",
            "email" => "Email",
            "courier" => "Servicio de mensajería",
            "estado" => "Estado del Pedido",
            "peso" => "Peso del paquete",
            "tarifa" => "Tarifa del paquete",
            "peso_tarifa" => "Valor del paquete",
            "USA_fecha_compra" => "Fecha de compra en USA",
            "USA_fecha_estimada_llegada" => "Fecha estimada de llegada en USA",
            "USA_fecha_llegada" => "Fecha de llegada en USA",
            "USA_fecha_envio" => "Fecha de envio en USA",
            "ABI_fecha_estimada_llegada" => "Fecha estimada de llegada a Abi Courier",
            "ABI_fecha_llegada" =>  "Fecha de llegada a Abi Courier",
            "ABI_fecha_entrega" =>  "Fecha de entrega en Abi Courier",
        ];

        $data = [
            "IdClientes" => $request->idPedido_edit,
            "ciudad" => $request->ciudad,
            "cedula" => $request->cedula,
            "nombres" => $request->nombres,
            "email" => $request->email,
            "courier" => $request->InputCourier_edit,
            "estado" => $request->InputEstado_edit,
            "peso" => $request->InputPeso_edit,
            "tarifa" => $request->InputTarifa_edit,
            "peso_tarifa" => $request->pesoTarifaHidden_edit,
            "USA_fecha_compra" => $request->USAfechaCompra,
            "USA_fecha_estimada_llegada" => $request->USAfechaEstimadaLlegada,
            "USA_fecha_llegada" => $request->USAfechaLlegada,
            "USA_fecha_envio" => $request->USAfechaEnvio,
            "ABI_fecha_estimada_llegada" => $request->ABIfechaEstimadaLlegada,
            "ABI_fecha_llegada" => $request->ABIfechaLlegada,
            "ABI_fecha_entrega" => $request->ABIfechaEntrega,
            "MFecha" => now(),
        ];

        if ($dato) {
            $allEqual = true;
            foreach ($data as $campo => $valor) {
                if ($dato->$campo !== $valor) {
                    $dato->$campo = $valor;
                    $dato->save();
                    $allEqual = false;
                }
            }
            if ($allEqual) {
                return response()->json(['errors' => 'Todos los valores son iguales a los ya ingresados. El registro ya está actualizado.'], 500);
            } else {
                return response()->json(['success' => 'Registro actualizado con éxito.']);
            }
        } else {
            return response()->json(['errors' => 'No se encontró ningún registro con el ID proporcionado.'], 500);
        }
    }

    public function eliminarImagen($id, Request $request)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado.');
        }

        $pedido_input = $request->input('delete_imagenPedido');
        $paquete_input = $request->input('delete_imagenPaquete');
        $pago_input = $request->input('delete_imagenPago');

        $rutaArchivo = '';

        if ($pedido_input && $pedido_input === $cliente->imagen_pedido) {
            $rutaArchivo = 'public/imagenes_abicourier/' . $pedido_input;
            $cliente->imagen_pedido = null;
        } elseif ($paquete_input && $paquete_input === $cliente->imagen_paquete) {
            $rutaArchivo = 'public/imagenes_abicourier/' . $paquete_input;
            $cliente->imagen_paquete = null;
        } elseif ($pago_input && $pago_input === $cliente->imagen_pago) {
            $rutaArchivo = 'public/imagenes_abicourier/' . $pago_input;
            $cliente->imagen_pago = null;
        }

        if (!empty($rutaArchivo) && Storage::exists($rutaArchivo)) {
            Storage::delete($rutaArchivo);
            $cliente->save();           
            return redirect()->back()->with('success', 'El archivo ha sido eliminado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'El archivo no existe o no coincide.');
        }
    }

    public function subirImagen($id, Request $request)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente no encontrado.');
        }

        $imagenPedido = $request->file('ImagenPedido') ?? null;
        $imagenPaquete = $request->file('ImagenPaquete') ?? null;
        $imagenPago = $request->file('ImagenPago') ?? null;


        if ($imagenPedido) {
            $nombreImagenPedido = $this->guardarImagen($id, $imagenPedido, 'pedido', $cliente->cedula);
            $cliente->imagen_pedido = $nombreImagenPedido;
        }

        if ($imagenPaquete) {
            $nombreImagenPaquete = $this->guardarImagen($id, $imagenPaquete, 'paquete', $cliente->cedula);
            $cliente->imagen_paquete = $nombreImagenPaquete;
        }

        if ($imagenPago) {
            $nombreImagenPago = $this->guardarImagen($id, $imagenPago, 'pago', $cliente->cedula);
            $cliente->imagen_pago = $nombreImagenPago;
        }

        $cliente->save();
        
        if ($cliente->save()) {
            return response()->json(['success' => 'Imagen guardado correctamente']);
        } else {
            return response()->json(['errors' => 'Error al guardar las imágenes'], 500);
        }
    }

    function guardarImagen($id, $imagen, $tipo, $cedula)
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
