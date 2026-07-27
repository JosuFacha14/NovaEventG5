@extends('adminlte::page')

@section('title', 'Gestión de Reservas de Inventario')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Reservas de Inventario</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearReserva">
                <i class="bi bi-plus-circle me-1"></i> Nueva Reserva
            </button>
        </div>
    </div>
@stop

@section('content')

    {{-- Alertas de sesión --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de reservas --}}
    <x-adminlte-card title="Listado de Reservas" icon="bi bi-clipboard-check-fill" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblReservas" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Ítem</th>
                        <th>Evento</th>
                        <th>Cantidad</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Solicitante</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $res)
                        <tr>
                            <td class="text-center">{{ $res['COD_RESERVA'] ?? '—' }}</td>
                            <td>{{ $res['NOM_ITEM'] ?? $res['COD_ITEM'] ?? '—' }}</td>
                            <td class="text-center">{{ $res['NOM_EVENTO'] ?? $res['COD_EVENTO'] ?? '—' }}</td>
                            <td class="text-center">{{ $res['CAN_RESERVADA'] ?? '—' }}</td>
                            <td class="text-center">
                                {{ isset($res['FEC_INICIO_RESERVA']) ? substr($res['FEC_INICIO_RESERVA'], 0, 10) : '—' }}
                            </td>
                            <td class="text-center">
                                {{ isset($res['FEC_FIN_RESERVA']) ? substr($res['FEC_FIN_RESERVA'], 0, 10) : '—' }}
                            </td>
                            <td>{{ $res['NOM_SOLICITANTE'] ?? '—' }}</td>
                            <td class="text-center">
                                @php $estado = $res['IND_ESTADO_RESERVA'] ?? 'ACTIVA'; @endphp
                                @if($estado === 'ACTIVA')
                                    <span class="badge bg-success">Activa</span>
                                @elseif($estado === 'CANCELADA')
                                    <span class="badge bg-danger">Cancelada</span>
                                @else
                                    <span class="badge bg-secondary">Completada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar{{ $res['COD_RESERVA'] }}"
                                        title="Editar reserva">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal editar reserva --}}
                        <div class="modal fade" id="modalEditar{{ $res['COD_RESERVA'] }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form method="POST"
                                      action="{{ route('inventario.reservas.update', $res['COD_RESERVA']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Reserva #{{ $res['COD_RESERVA'] }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Cantidad reservada <span class="text-danger">*</span></label>
                                                    <input type="number" name="can_reservada" class="form-control"
                                                           value="{{ $res['CAN_RESERVADA'] ?? 1 }}" required min="1">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Fecha inicio <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="fec_inicio_res" class="form-control"
                                                           value="{{ isset($res['FEC_INICIO_RESERVA']) ? str_replace(' ', 'T', substr($res['FEC_INICIO_RESERVA'], 0, 16)) : '' }}"
                                                           required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Fecha fin <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="fec_fin_res" class="form-control"
                                                           value="{{ isset($res['FEC_FIN_RESERVA']) ? str_replace(' ', 'T', substr($res['FEC_FIN_RESERVA'], 0, 16)) : '' }}"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                    <select name="ind_estado_res" class="form-select" required>
                                                        <option value="ACTIVA"     {{ ($res['IND_ESTADO_RESERVA'] ?? '') === 'ACTIVA'     ? 'selected' : '' }}>Activa</option>
                                                        <option value="CANCELADA"  {{ ($res['IND_ESTADO_RESERVA'] ?? '') === 'CANCELADA'  ? 'selected' : '' }}>Cancelada</option>
                                                        <option value="COMPLETADA" {{ ($res['IND_ESTADO_RESERVA'] ?? '') === 'COMPLETADA' ? 'selected' : '' }}>Completada</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Solicitante</label>
                                                    <input type="text" name="nom_solicitante" class="form-control"
                                                           value="{{ $res['NOM_SOLICITANTE'] ?? '' }}" maxlength="100">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Notas</label>
                                                <textarea name="des_notas_res" class="form-control" rows="2">{{ $res['DES_NOTAS'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-save me-1"></i> Guardar cambios
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                No hay reservas de inventario registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- Modal crear reserva --}}
    <div class="modal fade" id="modalCrearReserva" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('inventario.reservas.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Nueva Reserva de Inventario</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ítem <span class="text-danger">*</span></label>
                                <select name="cod_item" class="form-select" required>
                                    <option value="" selected disabled>Seleccione un ítem...</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item['COD_ITEM'] }}">
                                            #{{ $item['COD_ITEM'] }} — {{ $item['NOM_ITEM'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Evento <span class="text-danger">*</span></label>
                                <select name="cod_evento_res" class="form-select" required>
                                    <option value="" selected disabled>Seleccione un evento...</option>
                                    @foreach($eventos as $evento)
                                        <option value="{{ $evento['COD_EVENTO'] }}">
                                            #{{ $evento['COD_EVENTO'] }} — {{ $evento['NOM_EVENTO'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cantidad Reservada <span class="text-danger">*</span></label>
                                <input type="number" name="can_reservada" class="form-control" required min="1"
                                       placeholder="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Solicitante</label>
                                <input type="text" name="nom_solicitante" class="form-control" maxlength="100"
                                       placeholder="Nombre del solicitante">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="fec_inicio_res" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="fec_fin_res" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notas adicionales</label>
                            <textarea name="des_notas_res" class="form-control" rows="2"
                                      placeholder="Observaciones..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar Reserva
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#tblReservas').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                pageLength: 10,
                order: [[0, 'asc']],
            });
        }

        // Select2 para los combos de ítem y evento (buscables)
        if (typeof $.fn.select2 !== 'undefined') {
            $('#modalCrearReserva select[name="cod_item"], #modalCrearReserva select[name="cod_evento_res"]').select2({
                dropdownParent: $('#modalCrearReserva'),
                width: '100%'
            });
        }
    });
</script>
@stop