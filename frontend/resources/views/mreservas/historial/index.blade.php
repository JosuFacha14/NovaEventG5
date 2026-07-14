@extends('adminlte::page')

@section('title', 'Historial de Reservaciones')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-12">
            <h3 class="mb-0">Historial de Cambios de Estado</h3>
        </div>
    </div>
@stop

@section('content')

    {{-- Alertas --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla --}}
    <x-adminlte-card title="Registro de Historial" icon="bi bi-clock-history" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblHistorial" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Reservación</th>
                        <th>Estado Anterior</th>
                        <th>Estado Nuevo</th>
                        <th>Usuario (Cod. Persona)</th>
                        <th>Fecha de Cambio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $h)
                        <tr>
                            <td class="text-center">{{ $h['CSC_HISTORIAL'] }}</td>
                            <td class="text-center">#{{ $h['COD_RESERVACION'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $h['IND_ESTADO_ANT'] }}</span>
                            </td>
                            <td class="text-center">
                                @if($h['IND_ESTADO_NUE'] === 'CONFIRMADA')
                                    <span class="badge bg-info">{{ $h['IND_ESTADO_NUE'] }}</span>
                                @elseif($h['IND_ESTADO_NUE'] === 'COMPLETADA')
                                    <span class="badge bg-success">{{ $h['IND_ESTADO_NUE'] }}</span>
                                @elseif($h['IND_ESTADO_NUE'] === 'CANCELADA')
                                    <span class="badge bg-danger">{{ $h['IND_ESTADO_NUE'] }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $h['IND_ESTADO_NUE'] }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $h['COD_PERSONA_CAM'] }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($h['FEC_ADICION'])->setTimezone('America/Costa_Rica')->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay registros en el historial.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

@stop

@section('plugins.Datatables', true)
