@extends('adminlte::page')

@section('title', 'Reporte de Ganancias')

@section('content_header')
    <h1 class="m-0 text-dark">Reporte de Ganancias y Utilidades</h1>
@endsection

@section('content')
    @php
        $ingresosTotales = 0;
        $costosTotales = 0;
        $utilidadTotal = 0;

        foreach($ganancias as $g) {
            $ingresosTotales += $g->MON_INGRESOS ?? 0;
            $costosTotales += $g->MON_COSTOS ?? 0;
            $utilidadTotal += $g->MON_UTILIDAD ?? 0;
        }

        $margenRentabilidad = $ingresosTotales > 0 ? ($utilidadTotal / $ingresosTotales) * 100 : 0;
    @endphp

    <!-- Tarjetas de Métricas -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>L. {{ number_format($ingresosTotales, 2) }}</h3>
                    <p>Ingresos Totales (Ventas)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>L. {{ number_format($costosTotales, 2) }}</h3>
                    <p>Costos Totales Deducidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-minus-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>L. {{ number_format($utilidadTotal, 2) }}</h3>
                    <p>Utilidad Neta General</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($margenRentabilidad, 1) }}%</h3>
                    <p>Margen de Rentabilidad</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Desglose de Utilidades por Evento</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-striped m-0">
                <thead>
                    <tr>
                        <th>Cód. Ganancia</th>
                        <th>Cód. Evento</th>
                        <th class="text-right">Ingresos</th>
                        <th class="text-right">Costos</th>
                        <th class="text-right">Utilidad Neta</th>
                        <th>Fecha de Cierre</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ganancias as $g)
                        <tr>
                            <td>{{ $g->COD_GANANCIA }}</td>
                            <td><span class="badge badge-primary">Evento #{{ $g->COD_EVENTO }}</span></td>
                            <td class="text-right text-success">L. {{ number_format($g->MON_INGRESOS, 2) }}</td>
                            <td class="text-right text-danger">L. {{ number_format($g->MON_COSTOS, 2) }}</td>
                            <td class="text-right font-weight-bold text-primary">L. {{ number_format($g->MON_UTILIDAD, 2) }}</td>
                            <td>{{ $g->FEC_CIERRE }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                No hay registros de ganancias o utilidades actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
