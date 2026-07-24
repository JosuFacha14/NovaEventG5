@extends('adminlte::page')

@section('title', 'Reporte de Inventario')

@section('content_header')
    <h1 class="m-0 text-dark">Reporte de Estado de Inventario</h1>
@endsection

@section('content')
    @php
        $totalArticulosUtilizados = 0;
        foreach($inventario as $inv) {
            $totalArticulosUtilizados += $inv->CAN_UTILIZADA ?? $inv->can_utilizada ?? 0;
        }
    @endphp

    <!-- Tarjeta de Resumen -->
    <div class="row">
        <div class="col-md-12">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-cubes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Artículos/Equipos Utilizados en Eventos</span>
                    <span class="info-box-number">{{ $totalArticulosUtilizados }} unidades</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                    <span class="progress-description">Suma total de materiales asignados</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Materiales -->
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title text-dark font-weight-bold">Asignación de Inventario y Estado de Retorno</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover m-0">
                <thead>
                    <tr class="text-dark">
                        <th>Cód. Reporte</th>
                        <th>Cód. Item</th>
                        <th>Cód. Evento</th>
                        <th>Cantidad Utilizada</th>
                        <th>Estado Final</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventario as $inv)
                        @php
                            $codReporte = $inv->COD_REPORTE ?? $inv->cod_reporte ?? $inv->COD_REP_INVENTARIO ?? 'N/A';
                            $codItem    = $inv->COD_ITEM ?? $inv->cod_item ?? 'N/A';
                            $codEvento  = $inv->COD_EVENTO ?? $inv->cod_evento ?? 'N/A';
                            $cantidad   = $inv->CAN_UTILIZADA ?? $inv->can_utilizada ?? 0;
                            $estado     = strtoupper($inv->IND_ESTADO_FINAL ?? $inv->ind_estado_final ?? $inv->DES_ESTADO_FINAL ?? $inv->des_estado_final ?? 'BUENO');
                            $obs        = $inv->OBS_REPORTE ?? $inv->obs_reporte ?? $inv->OBS_NOTAS ?? $inv->obs_notas ?? 'Sin observaciones';
                        @endphp
                        <tr class="text-dark">
                            <td class="font-weight-bold">{{ $codReporte }}</td>
                            <td><span class="badge badge-dark px-2 py-1">Item #{{ $codItem }}</span></td>
                            <td><span class="badge badge-info px-2 py-1">Evento #{{ $codEvento }}</span></td>
                            <td class="font-weight-bold text-dark">{{ $cantidad }}</td>
                            <td>
                                @if($estado == 'BUENO' || $estado == 'COMPLETO')
                                    <span class="badge badge-success px-2 py-1">{{ $estado }}</span>
                                @elseif($estado == 'DAÑADO' || $estado == 'PERDIDO')
                                    <span class="badge badge-danger px-2 py-1">{{ $estado }}</span>
                                @else
                                    <span class="badge badge-warning text-dark px-2 py-1">{{ $estado }}</span>
                                @endif
                            </td>
                            <td class="text-dark">{{ $obs }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-boxes d-block mb-2 fa-2x"></i>
                                El inventario de reportes de asignación está actualmente vacío.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection