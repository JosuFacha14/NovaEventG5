<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportesController extends Controller
{
    /**
     * Dashboard Principal de Reportes
     */
    public function index()
    {
        try {
            $responseCostos = Http::get('http://localhost:3000/api/costos-operativos');
            $costos = $responseCostos->successful() ? json_decode($responseCostos->body()) : [];
            
            $totalCostos = 0;
            if (is_array($costos)) {
                foreach ($costos as $c) {
                    $totalCostos += $c->MON_REAL ?? $c->mon_real ?? 0;
                }
            }

            $responseGanancias = Http::get('http://localhost:3000/api/ganancias');
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

        $datos = [
            'total_eventos'   => is_array($costos) ? count($costos) : 0,
            'total_ganancias' => $totalGanancias,
            'total_costos'    => $totalCostos,
        ];

        return view('mreportes.reportes.index', compact('datos'));
    }

    /**
     * Vista de Costos Operativos
     */
    public function costosIndex()
    {
        try {
            $response = Http::get('http://localhost:3000/api/costos-operativos');
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

    /**
     * Vista de Ganancias
     */
    public function gananciasIndex()
    {
        try {
            $response = Http::get('http://localhost:3000/api/ganancias');
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

    /**
     * Vista de Inventario
     */
    public function inventarioIndex()
    {
        try {
            $response = Http::get('http://localhost:3000/api/reportes-inventario');
            $inventario = $response->successful() ? json_decode($response->body()) : [];
        } catch (\Exception $e) {
            $inventario = [];
        }

        return view('mreportes.inventario.index', compact('inventario'));
    }
}