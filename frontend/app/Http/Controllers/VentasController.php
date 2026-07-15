<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VentasController extends Controller
{
    /**
     * URL BASE DE LA API
     */
    private $api = 'http://localhost:3000/api';


    /*=========================================================
    | CATEGORÍAS DE EVENTO
    =========================================================*/

    public function categoriasIndex()
    {
        try {

            $response = Http::get(
                $this->api . '/categorias-eventos',
                ['accion' => 'SEL_CATEGORIA']
            );

            $categorias = $response->successful()
                ? $response->json()
                : [];

        } catch (\Exception $e) {

            $categorias = [];

            return back()->with(
                'error',
                'No fue posible conectar con la API.'
            );
        }

        return view(
            'mventas.categorias-evento.index',
            compact('categorias')
        );
    }


    public function categoriasStore(Request $request)
    {
        $request->validate([
            'nom_categoria' => 'required|max:100',
            'des_categoria' => 'required|max:255'
        ]);

        try {

            $response = Http::post(
                $this->api . '/categorias-eventos',
                [
                    'accion' => 'INS_CATEGORIA',
                    'nom_categoria' => $request->nom_categoria,
                    'des_categoria' => $request->des_categoria
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('categorias-evento.index')
                    ->with(
                        'success',
                        'Categoría creada correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible guardar el registro.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    public function categoriasUpdate(Request $request, $id)
    {
        $request->validate([
            'nom_categoria' => 'required|max:100',
            'des_categoria' => 'required|max:255'
        ]);

        try {

            $response = Http::put(
                $this->api . '/categorias-eventos/' . $id,
                [
                    'accion' => 'UPD_CATEGORIA',
                    'nom_categoria' => $request->nom_categoria,
                    'des_categoria' => $request->des_categoria
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('categorias-evento.index')
                    ->with(
                        'success',
                        'Categoría actualizada correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible actualizar.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    public function categoriasDestroy($id)
    {
        try {

            $response = Http::put(
                $this->api . '/categorias-eventos/' . $id,
                [
                    'accion' => 'DEL_CATEGORIA'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('categorias-evento.index')
                    ->with(
                        'success',
                        'Categoría eliminada correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible eliminar.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    /*=========================================================
    | CICLOS DE EVENTO
    =========================================================*/

    public function ciclosIndex()
    {
        try {

            $response = Http::get(
                $this->api . '/ciclos-eventos',
                ['accion' => 'SEL_CICLO']
            );

            $ciclos = $response->successful()
                ? $response->json()
                : [];

        } catch (\Exception $e) {

            $ciclos = [];

            return back()->with(
                'error',
                'No fue posible conectar con la API.'
            );
        }

        return view(
            'mventas.ciclos-evento.index',
            compact('ciclos')
        );
    }


    public function ciclosStore(Request $request)
    {
        $request->validate([
            'nom_ciclo' => 'required|max:100',
            'des_ciclo' => 'required|max:255'
        ]);

        try {

            $response = Http::post(
                $this->api . '/ciclos-eventos',
                [
                    'accion' => 'INS_CICLO',
                    'nom_ciclo' => $request->nom_ciclo,
                    'des_ciclo' => $request->des_ciclo,
                    'usr_ingreso' => 'ADMIN'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('ciclos-evento.index')
                    ->with(
                        'success',
                        'Ciclo de evento creado correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible guardar el ciclo de evento.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    public function ciclosUpdate(Request $request, $id)
    {
        $request->validate([
            'nom_ciclo' => 'required|max:100',
            'des_ciclo' => 'required|max:255'
        ]);

        try {

            $response = Http::put(
                $this->api . '/ciclos-eventos/' . $id,
                [
                    'accion' => 'UPD_CICLO',
                    'nom_ciclo' => $request->nom_ciclo,
                    'des_ciclo' => $request->des_ciclo,
                    'ind_activo_ciclo' => '1'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('ciclos-evento.index')
                    ->with(
                        'success',
                        'Ciclo de evento actualizado correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible actualizar el ciclo de evento.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    public function ciclosDestroy($id)
    {
        try {

            $response = Http::put(
                $this->api . '/ciclos-eventos/' . $id,
                [
                    'accion' => 'DEL_CICLO'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('ciclos-evento.index')
                    ->with(
                        'success',
                        'Ciclo de evento dado de baja correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible dar de baja el ciclo de evento.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    /*=========================================================
    | GESTIÓN DE EVENTOS
    =========================================================*/

    /**
     * Mostrar listado de eventos
     *
     * También obtiene las categorías y ciclos activos
     * para llenar los select de los formularios.
     */
    public function eventosIndex()
    {
        try {

            // Obtener eventos
            $responseEventos = Http::get(
                $this->api . '/eventos',
                [
                    'accion' => 'SEL_EVENTO'
                ]
            );

            // Obtener categorías
            $responseCategorias = Http::get(
                $this->api . '/categorias-eventos',
                [
                    'accion' => 'SEL_CATEGORIA'
                ]
            );

            // Obtener ciclos
            $responseCiclos = Http::get(
                $this->api . '/ciclos-eventos',
                [
                    'accion' => 'SEL_CICLO'
                ]
            );


            $eventos = $responseEventos->successful()
                ? $responseEventos->json()
                : [];

            $categorias = $responseCategorias->successful()
                ? $responseCategorias->json()
                : [];

            $ciclos = $responseCiclos->successful()
                ? $responseCiclos->json()
                : [];


        } catch (\Exception $e) {

            $eventos = [];
            $categorias = [];
            $ciclos = [];

            return back()->with(
                'error',
                'No fue posible conectar con la API.'
            );
        }


        return view(
            'mventas.eventos.index',
            compact(
                'eventos',
                'categorias',
                'ciclos'
            )
        );
    }


    /**
     * Guardar nuevo evento
     */
    public function eventosStore(Request $request)
    {
        $request->validate([

            'cod_categoria' => 'required|integer',

            'cod_ciclo_evento' => 'required|integer',

            'cod_reservacion' => 'required|integer',

            'nom_evento' => 'required|max:150',

            'des_evento' => 'required',

            'fec_evento' => 'required|date',

            'hor_evento' => 'required',

            'des_lugar' => 'required|max:150',

            'num_capacidad' => 'required|integer|min:1',

            'ind_estado' => 'required|in:ACTIVO,CANCELADO,FINALIZADO'

        ]);


        try {

            $response = Http::post(
                $this->api . '/eventos',
                [

                    'accion' => 'INS_EVENTO',

                    'cod_categoria' =>
                        $request->cod_categoria,

                    'cod_ciclo_evento' =>
                        $request->cod_ciclo_evento,

                    'cod_reservacion' =>
                        $request->cod_reservacion,

                    'nom_evento' =>
                        $request->nom_evento,

                    'des_evento' =>
                        $request->des_evento,

                    'fec_evento' =>
                        $request->fec_evento,

                    'hor_evento' =>
                        $request->hor_evento,

                    'des_lugar' =>
                        $request->des_lugar,

                    'num_capacidad' =>
                        $request->num_capacidad,

                    'ind_estado' =>
                        $request->ind_estado

                ]
            );


            if ($response->successful()) {

                return redirect()
                    ->route('eventos.index')
                    ->with(
                        'success',
                        'Evento creado correctamente.'
                    );
            }


            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible guardar el evento.'
                );


        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Error al conectar con la API.'
                );
        }
    }


    /**
     * Actualizar evento
     *
     * Los campos que no se envíen quedarán NULL.
     * El procedimiento usa IFNULL y conserva
     * el valor existente en la base de datos.
     */
    public function eventosUpdate(Request $request, $id)
    {
        $request->validate([

            'nom_evento' => 'required|max:150',

            'fec_evento' => 'required|date',

            'hor_evento' => 'required',

            'des_lugar' => 'required|max:150',

            'ind_estado' =>
                'required|in:ACTIVO,CANCELADO,FINALIZADO'

        ]);


        try {

            $datos = [

                'accion' => 'UPD_EVENTO',

                'nom_evento' =>
                    $request->nom_evento,

                'fec_evento' =>
                    $request->fec_evento,

                'hor_evento' =>
                    $request->hor_evento,

                'des_lugar' =>
                    $request->des_lugar,

                'ind_estado_evento' =>
                    $request->ind_estado,

                'ind_activo' => '1'

            ];


            /*
             * Estos campos se envían solamente
             * si existen en el formulario.
             *
             * Si no existen, el procedimiento
             * conserva el valor actual.
             */

            if ($request->filled('cod_categoria')) {

                $datos['cod_categoria'] =
                    $request->cod_categoria;
            }


            if ($request->filled('cod_ciclo_evento')) {

                $datos['cod_ciclo_evento'] =
                    $request->cod_ciclo_evento;
            }


            if ($request->filled('cod_reservacion')) {

                $datos['cod_reservacion'] =
                    $request->cod_reservacion;
            }


            if ($request->filled('des_evento')) {

                $datos['des_evento'] =
                    $request->des_evento;
            }


            if ($request->filled('num_capacidad')) {

                $datos['num_capacidad'] =
                    $request->num_capacidad;
            }


            $response = Http::put(
                $this->api . '/eventos/' . $id,
                $datos
            );


            if ($response->successful()) {

                return redirect()
                    ->route('eventos.index')
                    ->with(
                        'success',
                        'Evento actualizado correctamente.'
                    );
            }


            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible actualizar el evento.'
                );


        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Error al conectar con la API.'
                );
        }
    }


    /**
     * Baja lógica del evento
     */
    public function eventosDestroy($id)
    {
        try {

            $response = Http::put(
                $this->api . '/eventos/' . $id,
                [
                    'accion' => 'DEL_EVENTO'
                ]
            );


            if ($response->successful()) {

                return redirect()
                    ->route('eventos.index')
                    ->with(
                        'success',
                        'Evento dado de baja correctamente.'
                    );
            }


            return back()->with(
                'error',
                'No fue posible dar de baja el evento.'
            );


        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }

        /*=========================================================
    | GESTIÓN DE BOLETOS
    =========================================================*/

    /**
     * Mostrar listado de boletos
     *
     * También obtiene los eventos activos
     * para llenar los select de los formularios.
     */
    public function boletosIndex()
    {
        try {

            // Obtener boletos
            $responseBoletos = Http::get(
                $this->api . '/boletos',
                [
                    'accion' => 'SEL_BOLETO'
                ]
            );

            // Obtener eventos activos
            $responseEventos = Http::get(
                $this->api . '/eventos',
                [
                    'accion' => 'SEL_EVENTO'
                ]
            );

            $boletos = $responseBoletos->successful()
                ? $responseBoletos->json()
                : [];

            $eventos = $responseEventos->successful()
                ? $responseEventos->json()
                : [];

        } catch (\Exception $e) {

            $boletos = [];
            $eventos = [];

            return back()->with(
                'error',
                'No fue posible conectar con la API.'
            );
        }

        return view(
            'mventas.boletos.index',
            compact(
                'boletos',
                'eventos'
            )
        );
    }


    /**
     * Guardar nuevo boleto
     */
    public function boletosStore(Request $request)
    {
        $request->validate([

            'cod_evento' =>
                'required|integer',

            'tip_boleto' =>
                'required|in:VIP,GENERAL,PREFERENCIAL,BACKSTAGE',

            'mon_precio' =>
                'required|numeric|min:0',

            'num_disponible' =>
                'required|integer|min:0',

            'des_boleto' =>
                'required'

        ]);

        try {

            $response = Http::post(
                $this->api . '/boletos',
                [
                    'accion' =>
                        'INS_BOLETO',

                    'cod_evento' =>
                        $request->cod_evento,

                    'tip_boleto' =>
                        $request->tip_boleto,

                    'mon_precio' =>
                        $request->mon_precio,

                    'num_disponible' =>
                        $request->num_disponible,

                    'des_boleto' =>
                        $request->des_boleto
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('boletos.index')
                    ->with(
                        'success',
                        'Boleto creado correctamente.'
                    );
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible guardar el boleto.'
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Error al conectar con la API.'
                );
        }
    }


    /**
     * Actualizar boleto
     */
    public function boletosUpdate(Request $request, $id)
    {
        $request->validate([

            'cod_evento' =>
                'required|integer',

            'tip_boleto' =>
                'required|in:VIP,GENERAL,PREFERENCIAL,BACKSTAGE',

            'mon_precio' =>
                'required|numeric|min:0',

            'num_disponible' =>
                'required|integer|min:0',

            'des_boleto' =>
                'required'

        ]);

        try {

            $response = Http::put(
                $this->api . '/boletos/' . $id,
                [
                    'accion' =>
                        'UPD_BOLETO',

                    'cod_evento' =>
                        $request->cod_evento,

                    'tip_boleto' =>
                        $request->tip_boleto,

                    'mon_precio' =>
                        $request->mon_precio,

                    'num_disponible' =>
                        $request->num_disponible,

                    'des_boleto' =>
                        $request->des_boleto,

                    'ind_activo' =>
                        '1'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('boletos.index')
                    ->with(
                        'success',
                        'Boleto actualizado correctamente.'
                    );
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible actualizar el boleto.'
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Error al conectar con la API.'
                );
        }
    }


    /**
     * Baja lógica del boleto
     */
    public function boletosDestroy($id)
    {
        try {

            $response = Http::put(
                $this->api . '/boletos/' . $id,
                [
                    'accion' =>
                        'DEL_BOLETO'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('boletos.index')
                    ->with(
                        'success',
                        'Boleto dado de baja correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible dar de baja el boleto.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }

    /*=========================================================
| GESTIÓN DE VENTAS
=========================================================*/

public function ventasIndex()
{
    try {

        // Obtener ventas
        $responseVentas = Http::get(
            $this->api . '/ventas',
            [
                'accion' => 'SEL_VENTA'
            ]
        );

        // Obtener clientes
        $responseClientes = Http::get(
            'http://localhost:3000/api/clientes',
            [
                'accion' => 'SEL_PA_CLIENTES'
            ]
        );

        // Obtener detalles de ventas
        $responseDetalles = Http::get(
            $this->api . '/detalle-ventas',
            [
                'accion' => 'SEL_DETALLE'
            ]
        );

        $ventas = $responseVentas->successful()
            ? $responseVentas->json()
            : [];

        $clientes = $responseClientes->successful()
            ? $responseClientes->json()
            : [];

        $detalles = $responseDetalles->successful()
            ? $responseDetalles->json()
            : [];

    } catch (\Exception $e) {

        $ventas = [];
        $clientes = [];
        $detalles = [];

        return back()->with(
            'error',
            'No fue posible conectar con la API.'
        );
    }

    return view(
        'mventas.ventas.index',
        compact(
            'ventas',
            'clientes',
            'detalles'
        )
    );
}


public function ventasStore(Request $request)
{
    $request->validate([

        'cod_cliente' =>
            'required|integer',

        'mon_total' =>
            'required|numeric|min:0',

        'metodo_pago' =>
            'required|in:EFECTIVO,TARJETA,TRANSFERENCIA',

        'estado_venta' =>
            'required|in:PAGADA,PENDIENTE,CANCELADA'

    ]);

    try {

        $response = Http::post(
            $this->api . '/ventas',
            [
                'accion' =>
                    'INS_VENTA',

                'cod_cliente' =>
                    $request->cod_cliente,

                'mon_total' =>
                    $request->mon_total,

                'metodo_pago' =>
                    $request->metodo_pago,

                'estado_venta' =>
                    $request->estado_venta
            ]
        );

        if ($response->successful()) {

            return redirect()
                ->route('ventas.index')
                ->with(
                    'success',
                    'Venta registrada correctamente.'
                );
        }

        return back()
            ->withInput()
            ->with(
                'error',
                'No fue posible guardar la venta.'
            );

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Error al conectar con la API.'
            );
    }
}


public function ventasUpdate(Request $request, $id)
{
    $request->validate([

        'cod_cliente' =>
            'required|integer',

        'mon_total' =>
            'required|numeric|min:0',

        'metodo_pago' =>
            'required|in:EFECTIVO,TARJETA,TRANSFERENCIA',

        'estado_venta' =>
            'required|in:PAGADA,PENDIENTE,CANCELADA'

    ]);

    try {

        $response = Http::put(
            $this->api . '/ventas/' . $id,
            [
                'accion' =>
                    'UPD_VENTA',

                'cod_cliente' =>
                    $request->cod_cliente,

                'mon_total' =>
                    $request->mon_total,

                'ind_metodo_pago' =>
                    $request->metodo_pago,

                'ind_estado_venta' =>
                    $request->estado_venta,

                'ind_activo' =>
                    '1'
            ]
        );

        if ($response->successful()) {

            return redirect()
                ->route('ventas.index')
                ->with(
                    'success',
                    'Venta actualizada correctamente.'
                );
        }

        return back()
            ->withInput()
            ->with(
                'error',
                'No fue posible actualizar la venta.'
            );

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Error al conectar con la API.'
            );
    }
}


public function ventasDestroy($id)
{
    try {

        $response = Http::put(
            $this->api . '/ventas/' . $id,
            [
                'accion' =>
                    'DEL_VENTA'
            ]
        );

        if ($response->successful()) {

            return redirect()
                ->route('ventas.index')
                ->with(
                    'success',
                    'Venta dada de baja correctamente.'
                );
        }

        return back()->with(
            'error',
            'No fue posible dar de baja la venta.'
        );

    } catch (\Exception $e) {

        return back()->with(
            'error',
            'Error al conectar con la API.'
        );
    }
}


/*=========================================================
| DETALLE DE VENTAS
=========================================================*/

public function ventasShow($id)
{
    try {

        $response = Http::get(
            $this->api . '/detalle-ventas',
            [
                'accion' => 'SEL_DETALLE',
                'cod_venta' => $id
            ]
        );

        $detalles = $response->successful()
            ? $response->json()
            : [];

    } catch (\Exception $e) {

        $detalles = [];

        return back()->with(
            'error',
            'No fue posible cargar el detalle de la venta.'
        );
    }

    return view(
        'mventas.ventas.show',
        compact('detalles')
    );
}

}