<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportesController extends Controller
{
    private $apiUrl = 'http://localhost:3000/api';

    /**
     * Dashboard Principal de Reportes
     */
    public function index()
    {
        try {
            $responseCostos = Http::get("{$this->apiUrl}/costos-operativos");
            $costos = $responseCostos->successful() ? json_decode($responseCostos->body()) : [];
            
            $totalCostos = 0;
            if (is_array($costos)) {
                foreach ($costos as $c) {
                    $totalCostos += $c->MON_REAL ?? $c->mon_real ?? 0;
                }
            }

            $responseGanancias = Http::get("{$this->apiUrl}/ganancias");
            $ganancias = $responseGanancias->successful() ? json_decode($responseGanancias->body()) : [];
            
            $totalGanancias = 0;
            if (is_array($ganancias)) {
                foreach ($ganancias as $g) {
                    $totalGanancias += $g->MON_UTILIDAD ?? $g->mon_utilidad ?? 0;
                }
            }

        } catch (\Exception $e) {
            $costos = [];
            $ganancias = [];
            $totalCostos = 0;
            $totalGanancias = 0;
        }

        $reportesLista = [];
        if (is_array($costos) && count($costos) > 0) {
            foreach ($costos as $key => $c) {
                $reportesLista[] = [
                    'id'       => $c->COD_COSTO ?? $c->cod_costo ?? ($key + 1),
                    'concepto' => $c->NOM_COSTO ?? $c->nom_costo ?? 'Reporte de Costo ' . ($key + 1),
                    'monto'    => $c->MON_REAL ?? $c->mon_real ?? 0,
                    'estado'   => $c->IND_ESTADO ?? $c->ind_estado ?? 'Activo'
                ];
            }
        } else {
            $reportesLista = [
                ['id' => 1, 'concepto' => 'Reporte Consolidado de Inventario', 'monto' => 12500.00, 'estado' => 'Activo'],
                ['id' => 2, 'concepto' => 'Balance General de Costos Operativos', 'monto' => 6000.00, 'estado' => 'Activo']
            ];
        }

        $datos = [
            'total_eventos'   => is_array($costos) ? count($costos) : 0,
            'total_ganancias' => $totalGanancias,
            'total_costos'    => $totalCostos,
            'reportes_lista'  => $reportesLista,
        ];

        return view('mreportes.reportes.index', compact('datos'));
    }

    // ==========================================
    // COSTOS OPERATIVOS
    // ==========================================

    public function costosIndex()
    {
        try {
            $response = Http::get("{$this->apiUrl}/costos-operativos");
            $costos = $response->successful() ? json_decode($response->body()) : [];
        } catch (\Exception $e) {
            $costos = [];
        }

        $totalPresupuestado = 0;
        $totalReal = 0;

        if (is_array($costos)) {
            foreach ($costos as $c) {
                $totalPresupuestado += $c->MON_PRESUPUESTADO ?? $c->mon_presupuestado ?? 0;
                $totalReal          += $c->MON_REAL ?? $c->mon_real ?? 0;
            }
        }

        return view('mreportes.costos.index', compact('costos', 'totalPresupuestado', 'totalReal'));
    }

    public function storeCosto(Request $request)
    {
        try {
            $response = Http::post("{$this->apiUrl}/costos-operativos", [
                'cod_evento'        => $request->input('cod_evento'),
                'mon_presupuestado' => $request->input('mon_presupuestado'),
                'mon_real'          => $request->input('mon_real'),
                'ind_categoria'     => $request->input('ind_categoria', 'OPERATIVO'),
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
                'mon_presupuestado' => $request->input('mon_presupuestado'),
                'mon_real'          => $request->input('mon_real'),
                'ind_categoria'     => $request->input('ind_categoria', 'OPERATIVO'),
                'ind_estado'        => $request->input('ind_estado', 'ACTIVO'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Costo actualizado correctamente.']);
            }

            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    public function darDeBajaCosto($id)
    {
        try {
            $response = Http::put("{$this->apiUrl}/costos-operativos/{$id}", [
                'ind_estado' => 'INACTIVO'
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Registro dado de baja correctamente.']);
            }

            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // GANANCIAS Y UTILIDADES
    // ==========================================

    public function gananciasIndex()
    {
        try {
            $response = Http::get("{$this->apiUrl}/ganancias");
            $ganancias = $response->successful() ? json_decode($response->body()) : [];
        } catch (\Exception $e) {
            $ganancias = [];
        }

        $totalIngresos = 0;
        $totalCostos   = 0;
        $totalUtilidad = 0;

        if (is_array($ganancias)) {
            foreach ($ganancias as $g) {
                $totalIngresos += $g->MON_INGRESOS ?? $g->mon_ingresos ?? 0;
                $totalCostos   += $g->MON_COSTOS ?? $g->mon_costos ?? 0;
                $totalUtilidad += $g->MON_UTILIDAD ?? $g->mon_utilidad ?? 0;
            }
        }

        return view('mreportes.ganancias.index', compact('ganancias', 'totalIngresos', 'totalCostos', 'totalUtilidad'));
    }

    public function storeGanancia(Request $request)
    {
        try {
            $response = Http::post("{$this->apiUrl}/ganancias", [
                'cod_evento'   => $request->input('cod_evento'),
                'mon_ingresos' => $request->input('mon_ingresos'),
                'mon_costos'   => $request->input('mon_costos'),
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
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Ganancia actualizada correctamente.']);
            }

            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    public function darDeBajaGanancia($id)
    {
        try {
            $response = Http::put("{$this->apiUrl}/ganancias/{$id}", [
                'ind_estado' => 'INACTIVO'
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Ganancia dada de baja.']);
            }

            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // REPORTE DE INVENTARIO
    // ==========================================

    public function inventarioIndex()
    {
        try {
            $response = Http::get("{$this->apiUrl}/reportes-inventario");
            $inventario = $response->successful() ? json_decode($response->body()) : [];
        } catch (\Exception $e) {
            $inventario = [];
        }

        return view('mreportes.inventario.index', compact('inventario'));
    }

    public function storeInventario(Request $request)
    {
        try {
            $response = Http::post("{$this->apiUrl}/reportes-inventario", [
                'cod_item'          => $request->input('cod_item'),
                'cod_evento'        => $request->input('cod_evento'),
                'can_utilizada'     => $request->input('can_utilizada'),
                'des_observaciones' => $request->input('des_observaciones'),
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
                'cod_item'          => $request->input('cod_item'),
                'cod_evento'        => $request->input('cod_evento'),
                'can_utilizada'     => $request->input('can_utilizada'),
                'des_observaciones' => $request->input('des_observaciones'),
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Registro de inventario actualizado.']);
            }

            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }

    public function darDeBajaInventario($id)
    {
        try {
            $response = Http::put("{$this->apiUrl}/reportes-inventario/{$id}", [
                'ind_estado' => 'INACTIVO'
            ]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Registro de inventario dado de baja.']);
            }

            return response()->json(['success' => false, 'message' => $response->body()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()], 500);
        }
    }
}