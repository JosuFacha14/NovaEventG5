@extends('adminlte::page')

@section('title', 'Reporte de Costos Operativos')

@section('content_header')
    <h1 class="m-0 text-dark">Reporte de Costos Operativos</h1>
@endsection

@section('content')
    <style>
        .tabla-costos td, .tabla-costos th {
            color: #212529 !important;
        }
    </style>

    <!-- Tarjetas de Resumen -->
    <div class="row">
        <div class="col-md-6">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Presupuestado</span>
                    <span class="info-box-number">L. {{ number_format($totalPresupuestado ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box bg-gradient-danger">
                <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Real Ejecutado</span>
                    <span class="info-box-number">L. {{ number_format($totalReal ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Costos Operativos -->
    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title text-dark font-weight-bold">Detalle de Costos por Evento</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover m-0 tabla-costos">
                <thead>
                    <tr class="text-dark">
                        <th>Cód. Costo</th>
                        <th>Cód. Evento</th>
                        <th>Monto Presupuestado</th>
                        <th>Monto Real</th>
                        <th>Desviación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($costos as $costo)
                        @php
                            $codCosto      = $costo->COD_COSTO ?? $costo->cod_costo ?? $costo->COD_COSTO_OPERATIVO ?? 'N/A';
                            $codEvento     = $costo->COD_EVENTO ?? $costo->cod_evento ?? 'N/A';
                            $presupuestado = $costo->MON_PRESUPUESTADO ?? $costo->mon_presupuestado ?? 0;
                            $real          = $costo->MON_REAL ?? $costo->mon_real ?? 0;
                            $desviacion    = $real - $presupuestado;
                        @endphp
                        <tr>
                            <td class="font-weight-bold">{{ $codCosto }}</td>
                            <td><span class="badge bg-info text-white px-2 py-1" style="font-size: 0.9rem;">Evento #{{ $codEvento }}</span></td>
                            <td>L. {{ number_format($presupuestado, 2) }}</td>
                            <td class="font-weight-bold">L. {{ number_format($real, 2) }}</td>
                            <td>
                                @if($desviacion > 0)
                                    <span class="badge bg-danger text-white px-2 py-1">+L. {{ number_format($desviacion, 2) }}</span>
                                @else
                                    <span class="badge bg-success text-white px-2 py-1">L. {{ number_format($desviacion, 2) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-file-invoice-dollar d-block mb-2 fa-2x"></i>
                                No hay costos operativos registrados actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection