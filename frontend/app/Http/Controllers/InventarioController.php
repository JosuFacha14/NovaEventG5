<?php

namespace App\Http\Controllers;

use App\Services\InventarioService;
use Illuminate\Http\Request;
use Throwable;

class InventarioController extends Controller
{
    protected InventarioService $svc;

    public function __construct(InventarioService $svc)
    {
        $this->svc = $svc;
    }

    // -------------------------------------------------------------------------
    // IN_CATEGORIAS_INVENTARIO
    // -------------------------------------------------------------------------

    public function categoriasIndex()
    {
        try {
            $categorias = $this->svc->listarCategorias();
        } catch (Throwable $e) {
            $categorias = [];
            session()->flash('error', 'No se pudo cargar las categorías: ' . $e->getMessage());
        }

        return view('minventario.categorias.index', compact('categorias'));
    }

    public function categoriasStore(Request $request)
    {
        $request->validate([
            'nom_categoria' => 'required|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'des_categoria' => 'nullable|string',
            'des_icono'     => 'nullable|string|max:50',
        ]);

        try {
            $this->svc->crearCategoria(array_merge(
                $request->only(['nom_categoria', 'des_categoria', 'des_icono']),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Categoría creada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear categoría: ' . $e->getMessage());
        }

        return redirect()->route('inventario.categorias.index');
    }

    public function categoriasUpdate(Request $request, int $id)
    {
        $request->validate([
            'nom_categoria' => 'required|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'des_categoria' => 'nullable|string',
            'des_icono'     => 'nullable|string|max:50',
        ]);

        try {
            $this->svc->actualizarCategoria($id, array_merge(
                $request->only(['nom_categoria', 'des_categoria', 'des_icono']),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Categoría actualizada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar: ' . $e->getMessage());
        }

        return redirect()->route('inventario.categorias.index');
    }

    public function categoriasBaja(int $id)
    {
        try {
            $this->svc->darBajaCategoria($id, session('usuario', 'admin'));
            session()->flash('success', 'Categoría desactivada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al desactivar: ' . $e->getMessage());
        }

        return redirect()->route('inventario.categorias.index');
    }

    // -------------------------------------------------------------------------
    // IN_ALMACENES
    // -------------------------------------------------------------------------

    public function almacenesIndex()
    {
        try {
            $almacenes = $this->svc->listarAlmacenes();
        } catch (Throwable $e) {
            $almacenes = [];
            session()->flash('error', 'No se pudo cargar los almacenes: ' . $e->getMessage());
        }

        return view('minventario.almacenes.index', compact('almacenes'));
    }

    public function almacenesStore(Request $request)
    {
        $request->validate([
            'nom_almacen'   => 'required|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'dir_ubicacion' => 'nullable|string|max:200',
            'can_capacidad' => 'nullable|integer|min:0',
        ]);

        try {
            $this->svc->crearAlmacen(array_merge(
                $request->only(['nom_almacen', 'dir_ubicacion', 'can_capacidad']),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Almacén creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear almacén: ' . $e->getMessage());
        }

        return redirect()->route('inventario.almacenes.index');
    }

    public function almacenesUpdate(Request $request, int $id)
    {
        $request->validate([
            'nom_almacen'   => 'required|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'dir_ubicacion' => 'nullable|string|max:200',
            'can_capacidad' => 'nullable|integer|min:0',
        ]);

        try {
            $this->svc->actualizarAlmacen($id, array_merge(
                $request->only(['nom_almacen', 'dir_ubicacion', 'can_capacidad']),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Almacén actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar: ' . $e->getMessage());
        }

        return redirect()->route('inventario.almacenes.index');
    }

    public function almacenesBaja(int $id)
    {
        try {
            $this->svc->darBajaAlmacen($id, session('usuario', 'admin'));
            session()->flash('success', 'Almacén desactivado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al desactivar: ' . $e->getMessage());
        }

        return redirect()->route('inventario.almacenes.index');
    }

    // -------------------------------------------------------------------------
    // IN_INVENTARIO_ITEM
    // -------------------------------------------------------------------------

    public function itemsIndex()
    {
        try {
            $items = $this->svc->listarItems();
        } catch (Throwable $e) {
            $items = [];
            session()->flash('error', 'No se pudo cargar los ítems: ' . $e->getMessage());
        }

        // Datos para los dropdowns de categoría y almacén
        try { $categorias = $this->svc->listarCategorias(); } catch (Throwable $e) { $categorias = []; }
        try { $almacenes  = $this->svc->listarAlmacenes();  } catch (Throwable $e) { $almacenes  = []; }

        return view('minventario.items.index', compact('items', 'categorias', 'almacenes'));
    }

    public function itemsStore(Request $request)
    {
        $request->validate([
            'nom_item'      => 'required|string|max:150|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'des_item'      => 'nullable|string',
            'can_total'     => 'required|integer|min:0',
            'can_disponible'=> 'required|integer|min:0',
            'cod_categoria' => 'nullable|integer',
            'cod_almacen'   => 'nullable|integer',
            'mon_costo'     => 'nullable|numeric|min:0',
        ]);

        try {
            $this->svc->crearItem(array_merge(
                $request->only([
                    'nom_item', 'des_item', 'can_total', 'can_disponible',
                    'cod_categoria', 'cod_almacen', 'cod_item_unico',
                    'fec_adquisicion', 'mon_costo',
                ]),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Ítem creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear ítem: ' . $e->getMessage());
        }

        return redirect()->route('inventario.items.index');
    }

    public function itemsUpdate(Request $request, int $id)
    {
        $request->validate([
            'nom_item'      => 'required|string|max:150|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'can_total'     => 'required|integer|min:0',
            'can_disponible'=> 'required|integer|min:0',
        ]);

        try {
            $this->svc->actualizarItem($id, array_merge(
                $request->only([
                    'nom_item', 'des_item', 'can_total', 'can_disponible',
                    'cod_item_unico', 'fec_adquisicion', 'mon_costo',
                ]),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Ítem actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar: ' . $e->getMessage());
        }

        return redirect()->route('inventario.items.index');
    }

    public function itemsBaja(int $id)
    {
        try {
            $this->svc->darBajaItem($id, session('usuario', 'admin'));
            session()->flash('success', 'Ítem dado de baja correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al dar de baja: ' . $e->getMessage());
        }

        return redirect()->route('inventario.items.index');
    }

    // -------------------------------------------------------------------------
    // IN_RESERVAS_INVENTARIO
    // -------------------------------------------------------------------------

    public function reservasIndex()
    {
        $reservas = [];
        $items    = [];
        $eventos  = [];

        try {
            $reservas = $this->svc->listarReservas();
        } catch (Throwable $e) {
            session()->flash('error', 'No se pudo cargar las reservas: ' . $e->getMessage());
        }

        // Datos para los dropdowns de ítem y evento
        try { $items   = $this->svc->listarItems();   } catch (Throwable $e) { $items   = []; }
        try { $eventos = $this->svc->listarEventos(); } catch (Throwable $e) { $eventos = []; }

        return view('minventario.reservas.index', compact('reservas', 'items', 'eventos'));
    }

    public function reservasStore(Request $request)
    {
        $request->validate([
            'cod_item'       => 'required|integer',
            'cod_evento_res' => 'required|integer',
            'can_reservada'  => 'required|integer|min:1',
            'fec_inicio_res' => 'required|date',
            'fec_fin_res'    => 'required|date|after_or_equal:fec_inicio_res',
        ]);

        try {
            $this->svc->crearItem(array_merge(
                $request->only([
                    'cod_item', 'cod_evento_res', 'can_reservada',
                    'fec_inicio_res', 'fec_fin_res', 'nom_solicitante', 'des_notas_res',
                ]),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Reserva creada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear reserva: ' . $e->getMessage());
        }

        return redirect()->route('inventario.reservas.index');
    }

    public function reservasUpdate(Request $request, int $id)
    {
        $request->validate([
            'can_reservada'    => 'required|integer|min:1',
            'fec_inicio_res'   => 'required|date',
            'fec_fin_res'      => 'required|date|after_or_equal:fec_inicio_res',
            'ind_estado_res'   => 'required|in:ACTIVA,CANCELADA,COMPLETADA',
            'nom_solicitante'  => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'des_notas_res'    => 'nullable|string',
        ]);

        try {
            $this->svc->actualizarReserva($id, array_merge(
                $request->only([
                    'can_reservada', 'fec_inicio_res', 'fec_fin_res',
                    'ind_estado_res', 'nom_solicitante', 'des_notas_res',
                ]),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Reserva actualizada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar reserva: ' . $e->getMessage());
        }

        return redirect()->route('inventario.reservas.index');
    }

    // -------------------------------------------------------------------------
    // IN_ASIGNACION_EVENTO
    // -------------------------------------------------------------------------

    public function asignacionesIndex()
    {
        try {
            $asignaciones = $this->svc->listarAsignaciones();
        } catch (Throwable $e) {
            $asignaciones = [];
            session()->flash('error', 'No se pudo cargar las asignaciones: ' . $e->getMessage());
        }

        // Datos para los dropdowns de ítem y evento
        try { $items   = $this->svc->listarItems();   } catch (Throwable $e) { $items   = []; }
        try { $eventos = $this->svc->listarEventos(); } catch (Throwable $e) { $eventos = []; }

        return view('minventario.asignaciones.index', compact('asignaciones', 'items', 'eventos'));
    }
    public function asignacionesStore(Request $request)
    {
        $request->validate([
            'cod_evento'    => 'required|integer',
            'cod_item'      => 'required|integer',
            'can_asignada'  => 'required|integer|min:1',
            'fec_salida'    => 'required|date',
            'nom_resp_asig' => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
        ]);

        try {
            // Renombramos cod_evento -> cod_evento_asig para que coincida con INSERT_INVENTARIO
            $datos = $request->only([
                'cod_item', 'can_asignada', 'fec_salida',
                'fec_retorno', 'nom_resp_asig', 'des_observaciones',
            ]);
            $datos['cod_evento_asig'] = $request->input('cod_evento');
            $datos['usr_registro']    = session('usuario', 'admin');

            $this->svc->crearItemConAsignacion($datos);
            session()->flash('success', 'Asignación creada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear asignación: ' . $e->getMessage());
        }

        return redirect()->route('inventario.asignaciones.index');
    }

    public function asignacionesUpdate(Request $request, int $id)
    {
        $request->validate([
            'can_asignada'    => 'required|integer|min:1',
            'ind_estado_asig' => 'required|in:PENDIENTE,ENTREGADO,RETORNADO,PERDIDO',
            'ind_condicion'   => 'nullable|in:BUENO,DANIADO,PERDIDO',
            'nom_resp_asig'   => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+$/',
            'des_observaciones' => 'nullable|string',
        ]);

        try {
            $this->svc->actualizarAsignacion($id, array_merge(
                $request->only([
                    'can_asignada', 'fec_salida', 'fec_retorno',
                    'ind_estado_asig', 'ind_condicion', 'nom_resp_asig', 'des_observaciones',
                ]),
                ['usr_registro' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Asignación actualizada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar asignación: ' . $e->getMessage());
        }

        return redirect()->route('inventario.asignaciones.index');
    }
}
