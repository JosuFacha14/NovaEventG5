<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReservacionService;
use App\Services\PersonasService;
use Throwable;

class ReservacionController extends Controller
{
    public function __construct(
        protected ReservacionService $svc,
        protected PersonasService $personasSvc
    ) {}

    //RE_ESPACIO

    public function espaciosIndex()
    {
        try {
            $espacios = $this->svc->listarEspacios();
        } catch (Throwable $e) {
            $espacios = [];
            session()->flash('error', 'No se pudo cargar la lista de espacios: ' . $e->getMessage());
        }

        return view('mreservas.espacios.index', compact('espacios'));
    }

    public function espaciosStore(Request $request)
    {
        $request->validate([
            'nom_espacio'      => 'required|string|max:100',
            'can_capacidad'    => 'required|integer|min:1',
            'tip_espacio'      => 'required|in:SALON,AUDITORIO,AREA_EXTERIOR,SALA_REUNION',
            'mon_precio_hora'  => 'required|numeric|min:0',
        ]);

        try {
            $this->svc->crearEspacio(array_merge(
                $request->only(['nom_espacio', 'can_capacidad', 'tip_espacio', 'mon_precio_hora']),
                [
                    'ind_estado'  => 'DISPONIBLE',
                    'usr_ingreso' => session('usuario', 'admin')
                ]
            ));
            session()->flash('success', 'Espacio creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear espacio: ' . $e->getMessage());
        }

        return redirect()->route('espacios.index');
    }

    public function espaciosUpdate(Request $request, int $id)
    {
        if ($request->input('accion') === 'SOFT_DELETE') {
            try {
                $estado = $request->input('ind_estado', 'INACTIVO');
                $this->svc->cambiarEstadoEspacio($id, $estado);
                session()->flash('success', 'Estado del espacio actualizado correctamente.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al cambiar estado del espacio: ' . $e->getMessage());
            }
            return redirect()->route('espacios.index');
        }

        $request->validate([
            'nom_espacio'      => 'required|string|max:100',
            'can_capacidad'    => 'required|integer|min:1',
            'tip_espacio'      => 'required|in:SALON,AUDITORIO,AREA_EXTERIOR,SALA_REUNION',
            'ind_estado'       => 'required|in:DISPONIBLE,MANTENIMIENTO,INACTIVO',
            'mon_precio_hora'  => 'required|numeric|min:0',
        ]);

        try {
            $this->svc->actualizarEspacio($id, array_merge(
                $request->only(['nom_espacio', 'can_capacidad', 'tip_espacio', 'ind_estado', 'mon_precio_hora']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Espacio actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar espacio: ' . $e->getMessage());
        }

        return redirect()->route('espacios.index');
    }

    //RE_RESERVACION

    public function reservacionesIndex()
    {
        try {
            $reservaciones = $this->svc->listarReservaciones();
            $espacios      = $this->svc->listarEspacios();
            $clientes      = $this->personasSvc->listarClientes();
            $empleados     = $this->personasSvc->listarEmpleados();
        } catch (Throwable $e) {
            $reservaciones = $espacios = $clientes = $empleados = [];
            session()->flash('error', 'No se pudieron cargar los datos de reservaciones: ' . $e->getMessage());
        }

        return view('mreservas.reservaciones.index', compact('reservaciones', 'espacios', 'clientes', 'empleados'));
    }

    public function reservacionesStore(Request $request)
    {
        $request->validate([
            'cod_espacio'       => 'required|integer',
            'cod_cliente'       => 'required|integer',
            'cod_empleado'      => 'required|integer',
            'fec_inicio_date'   => 'required|date',
            'fec_inicio_time'   => 'required',
            'fec_fin_date'      => 'required|date',
            'fec_fin_time'      => 'required',
            'des_notas'         => 'nullable|string',
        ]);

        try {
            $datos = $request->only(['cod_espacio', 'cod_cliente', 'cod_empleado', 'des_notas']);
            $datos['fec_inicio'] = $request->input('fec_inicio_date') . ' ' . $request->input('fec_inicio_time') . ':00';
            $datos['fec_fin']    = $request->input('fec_fin_date') . ' ' . $request->input('fec_fin_time') . ':00';
            $datos['ind_estado'] = 'PENDIENTE';
            $datos['usr_ingreso'] = session('usuario', 'admin');

            $this->svc->crearReservacion($datos);
            session()->flash('success', 'Reservación creada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear reservación: ' . $e->getMessage());
        }

        return redirect()->route('reservaciones.index');
    }

    public function reservacionesUpdate(Request $request, int $id)
    {
        if ($request->has('accion') && in_array($request->input('accion'), ['CONFIRMAR', 'CANCELAR', 'COMPLETAR'])) {
            try {
                $estadoMapping = [
                    'CONFIRMAR' => 'CONFIRMADA',
                    'CANCELAR'  => 'CANCELADA',
                    'COMPLETAR' => 'COMPLETADA'
                ];
                $nuevoEstado = $estadoMapping[$request->input('accion')];
                
                // Obtener estado anterior
                $reservacionAnt = $this->svc->obtenerReservacion($id);
                $estadoAnt = $reservacionAnt[0]['IND_ESTADO'] ?? 'PENDIENTE';

                // Cambiar estado
                $this->svc->cambiarEstadoReservacion($id, $nuevoEstado);
                
                // Registrar en historial
                $this->svc->crearHistorial([
                    'cod_reservacion' => $id,
                    'ind_estado_ant'  => $estadoAnt,
                    'ind_estado_nue'  => $nuevoEstado,
                    'cod_persona_cam' => session('cod_empleado', 1) // o el id de usuario de session
                ]);
                
                session()->flash('success', 'Estado de la reservación actualizado a ' . $nuevoEstado . '.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al cambiar estado: ' . $e->getMessage());
            }
            return redirect()->route('reservaciones.index');
        }

        $request->validate([
            'cod_espacio'       => 'required|integer',
            'cod_cliente'       => 'required|integer',
            'cod_empleado'      => 'required|integer',
            'fec_inicio_date'   => 'required|date',
            'fec_inicio_time'   => 'required',
            'fec_fin_date'      => 'required|date',
            'fec_fin_time'      => 'required',
            'ind_estado'        => 'required|in:PENDIENTE,CONFIRMADA,CANCELADA,COMPLETADA',
            'des_notas'         => 'nullable|string',
        ]);

        try {
            $datos = $request->only(['cod_espacio', 'cod_cliente', 'cod_empleado', 'ind_estado', 'des_notas']);
            $datos['fec_inicio'] = $request->input('fec_inicio_date') . ' ' . $request->input('fec_inicio_time') . ':00';
            $datos['fec_fin']    = $request->input('fec_fin_date') . ' ' . $request->input('fec_fin_time') . ':00';
            $datos['usr_ingreso'] = session('usuario', 'admin');

            // Obtener estado anterior
            $reservacionAnt = $this->svc->obtenerReservacion($id);
            $estadoAnt = $reservacionAnt[0]['IND_ESTADO'] ?? 'PENDIENTE';

            $this->svc->actualizarReservacion($id, $datos);

            // Si el estado cambió en la edición general, registrar en historial
            if ($estadoAnt !== $datos['ind_estado']) {
                $this->svc->crearHistorial([
                    'cod_reservacion' => $id,
                    'ind_estado_ant'  => $estadoAnt,
                    'ind_estado_nue'  => $datos['ind_estado'],
                    'cod_persona_cam' => session('cod_empleado', 1) // o ID de usuario
                ]);
            }

            session()->flash('success', 'Reservación actualizada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar reservación: ' . $e->getMessage());
        }

        return redirect()->route('reservaciones.index');
    }

    //RE_ESPACIO_OCUPADO

    public function espaciosOcupadosIndex()
    {
        try {
            $ocupados = $this->svc->listarEspaciosOcupados();
            $espacios = $this->svc->listarEspacios();
        } catch (Throwable $e) {
            $ocupados = $espacios = [];
            session()->flash('error', 'No se pudieron cargar los datos: ' . $e->getMessage());
        }

        return view('mreservas.espacios_ocupados.index', compact('ocupados', 'espacios'));
    }

    public function espaciosOcupadosStore(Request $request)
    {
        $request->validate([
            'cod_espacio'     => 'required|integer',
            'fec_inicio_date' => 'required|date',
            'fec_inicio_time' => 'required',
            'fec_fin_date'    => 'required|date',
            'fec_fin_time'    => 'required',
            'des_motivo'      => 'nullable|string|max:200',
        ]);

        try {
            $datos = $request->only(['cod_espacio', 'des_motivo']);
            $datos['fec_inicio'] = $request->input('fec_inicio_date') . ' ' . $request->input('fec_inicio_time') . ':00';
            $datos['fec_fin']    = $request->input('fec_fin_date') . ' ' . $request->input('fec_fin_time') . ':00';
            $datos['usr_ingreso'] = session('usuario', 'admin');

            $this->svc->crearEspacioOcupado($datos);
            session()->flash('success', 'Bloqueo de espacio registrado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al registrar bloqueo: ' . $e->getMessage());
        }

        return redirect()->route('espacios_ocupados.index');
    }

    public function espaciosOcupadosUpdate(Request $request, int $id)
    {
        $request->validate([
            'cod_espacio'     => 'required|integer',
            'fec_inicio_date' => 'required|date',
            'fec_inicio_time' => 'required',
            'fec_fin_date'    => 'required|date',
            'fec_fin_time'    => 'required',
            'des_motivo'      => 'nullable|string|max:200',
        ]);

        try {
            $datos = $request->only(['cod_espacio', 'des_motivo']);
            $datos['fec_inicio'] = $request->input('fec_inicio_date') . ' ' . $request->input('fec_inicio_time') . ':00';
            $datos['fec_fin']    = $request->input('fec_fin_date') . ' ' . $request->input('fec_fin_time') . ':00';
            $datos['usr_ingreso'] = session('usuario', 'admin');

            $this->svc->actualizarEspacioOcupado($id, $datos);
            session()->flash('success', 'Bloqueo actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar bloqueo: ' . $e->getMessage());
        }

        return redirect()->route('espacios_ocupados.index');
    }

    //RE_HISTORIAL_RESERVACION

    public function historialIndex()
    {
        try {
            $historial = $this->svc->listarHistorial();
        } catch (Throwable $e) {
            $historial = [];
            session()->flash('error', 'No se pudo cargar el historial: ' . $e->getMessage());
        }

        return view('mreservas.historial.index', compact('historial'));
    }
}
