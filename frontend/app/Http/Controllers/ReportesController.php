<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportesController extends Controller
{
    private $apiUrl = 'http://localhost:3000/api';

    // Helper: fetch a list from the API or return []
    private function fetchList(string $endpoint, array $params = []): array
    {
        try {
            $response = Http::get("{$this->apiUrl}{$endpoint}", $params);
            $data = $response->successful() ? json_decode($response->body()) : [];
            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    
    // RP_TABLA_REPORTES
    

    public function index()
    {
        $reportes  = $this->fetchList('/reportes');
        $ganancias = $this->fetchList('/ganancias');
        $costos    = $this->fetchList('/costos-operativos');

        $totalGanancias = array_sum(array_map(fn($g) => $g->MON_UTILIDAD ?? $g->mon_utilidad ?? 0, $ganancias));
        $totalCostos    = array_sum(array_map(fn($c) => $c->MON_REAL ?? $c->mon_real ?? 0, $costos));

        $datos = [
            'total_reportes'  => count($reportes),
            'total_ganancias' => $totalGanancias,
            'total_costos'    => $totalCostos,
            'reportes_lista'  => $reportes,
        ];

        return view('mreportes.reportes.index', compact('datos'));
    }

    public function storeReporte(Request $request)
    {
        try {
            $response = Http::post("{$this->apiUrl}/reportes", [
                'pv_tip_reporte'       => $request->input('tip_reporte'),
                'pd_fec_periodo_desde' => $request->input('fec_periodo_desde'),
                'pd_fec_periodo_hasta' => $request->input('fec_periodo_hasta'),
                'pv_obs_reporte'       => $request->input('obs_reporte'),
                'pv_usr_registro'      => session('usuario', 'admin'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Reporte registrado correctamente.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    public function updateReporte(Request $request, $id)
    {
        try {
            $response = Http::put("{$this->apiUrl}/reportes/{$id}", [
                'pv_tip_reporte'       => $request->input('tip_reporte'),
                'pd_fec_periodo_desde' => $request->input('fec_periodo_desde'),
                'pd_fec_periodo_hasta' => $request->input('fec_periodo_hasta'),
                'pv_obs_reporte'       => $request->input('obs_reporte'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Reporte actualizado correctamente.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    
    // COSTOS OPERATIVOS
    

    public function costosIndex()
    {
        $costos      = $this->fetchList('/costos-operativos');
        $eventos     = $this->fetchList('/eventos', ['accion' => 'SEL_EVENTO']);
        $reportes    = $this->fetchList('/reportes');
        $proveedores = $this->fetchList('/proveedores', ['accion' => 'SEL_PA_PROVEEDORES']);

        $totalPresupuestado = array_sum(array_map(fn($c) => $c->MON_PRESUPUESTADO ?? $c->mon_presupuestado ?? 0, $costos));
        $totalReal          = array_sum(array_map(fn($c) => $c->MON_REAL ?? $c->mon_real ?? 0, $costos));

        return view('mreportes.costos.index', compact('costos', 'totalPresupuestado', 'totalReal', 'eventos', 'reportes', 'proveedores'));
    }

    public function storeCosto(Request $request)
    {
        try {
            $response = Http::post("{$this->apiUrl}/costos-operativos", [
                'cod_evento'        => $request->input('cod_evento'),
                'cod_reporte'       => $request->input('cod_reporte'),
                'cod_proveedor'     => $request->input('cod_proveedor'),
                'ind_categoria'     => $request->input('ind_categoria'),
                'des_costo'         => $request->input('des_costo'),
                'mon_presupuestado' => $request->input('mon_presupuestado'),
                'mon_real'          => $request->input('mon_real'),
                'obs_costo'         => $request->input('obs_costo'),
                'usr_registro'      => session('usuario', 'admin'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Costo registrado correctamente.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    public function updateCosto(Request $request, $id)
    {
        try {
            $response = Http::put("{$this->apiUrl}/costos-operativos/{$id}", [
                'cod_evento'        => $request->input('cod_evento'),
                'cod_reporte'       => $request->input('cod_reporte'),
                'cod_proveedor'     => $request->input('cod_proveedor'),
                'ind_categoria'     => $request->input('ind_categoria'),
                'des_costo'         => $request->input('des_costo'),
                'mon_presupuestado' => $request->input('mon_presupuestado'),
                'mon_real'          => $request->input('mon_real'),
                'obs_costo'         => $request->input('obs_costo'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Costo actualizado correctamente.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    
    // GANANCIAS Y UTILIDADES
    

    public function gananciasIndex()
    {
        $ganancias = $this->fetchList('/ganancias');
        $eventos   = $this->fetchList('/eventos', ['accion' => 'SEL_EVENTO']);

        $totalIngresos = array_sum(array_map(fn($g) => $g->MON_INGRESOS ?? $g->mon_ingresos ?? 0, $ganancias));
        $totalCostos   = array_sum(array_map(fn($g) => $g->MON_COSTOS ?? $g->mon_costos ?? 0, $ganancias));
        $totalUtilidad = array_sum(array_map(fn($g) => $g->MON_UTILIDAD ?? $g->mon_utilidad ?? 0, $ganancias));

        return view('mreportes.ganancias.index', compact('ganancias', 'totalIngresos', 'totalCostos', 'totalUtilidad', 'eventos'));
    }

    public function storeGanancia(Request $request)
    {
        try {
            $response = Http::post("{$this->apiUrl}/ganancias", [
                'cod_evento'   => $request->input('cod_evento'),
                'mon_ingresos' => $request->input('mon_ingresos'),
                'mon_costos'   => $request->input('mon_costos'),
                'mon_utilidad' => $request->input('mon_utilidad'),
                'fec_cierre'   => $request->input('fec_cierre'),
                'usr_registro' => session('usuario', 'admin'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Ganancia registrada correctamente.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    public function updateGanancia(Request $request, $id)
    {
        try {
            $response = Http::put("{$this->apiUrl}/ganancias/{$id}", [
                'cod_evento'   => $request->input('cod_evento'),
                'mon_ingresos' => $request->input('mon_ingresos'),
                'mon_costos'   => $request->input('mon_costos'),
                'mon_utilidad' => $request->input('mon_utilidad'),
                'fec_cierre'   => $request->input('fec_cierre'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Ganancia actualizada correctamente.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    
    // REPORTE DE INVENTARIO
    

    public function inventarioIndex()
    {
        $inventario = $this->fetchList('/reportes-inventario');
        $items      = $this->fetchList('/in/item');
        $eventos    = $this->fetchList('/eventos', ['accion' => 'SEL_EVENTO']);

        return view('mreportes.inventario.index', compact('inventario', 'items', 'eventos'));
    }

    public function storeInventario(Request $request)
    {
        try {
            $response = Http::post("{$this->apiUrl}/reportes-inventario", [
                'cod_item'         => $request->input('cod_item'),
                'cod_evento'       => $request->input('cod_evento'),
                'can_utilizada'    => $request->input('can_utilizada'),
                'des_estado_final' => $request->input('des_estado_final'),
                'obs_notas'        => $request->input('obs_notas'),
                'usr_registro'     => session('usuario', 'admin'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Registro de inventario guardado.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    public function updateInventario(Request $request, $id)
    {
        try {
            $response = Http::put("{$this->apiUrl}/reportes-inventario/{$id}", [
                'cod_item'         => $request->input('cod_item'),
                'cod_evento'       => $request->input('cod_evento'),
                'can_utilizada'    => $request->input('can_utilizada'),
                'des_estado_final' => $request->input('des_estado_final'),
                'obs_notas'        => $request->input('obs_notas'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Registro de inventario actualizado.']);
            }
            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }
}