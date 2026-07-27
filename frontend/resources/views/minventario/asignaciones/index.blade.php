@extends('adminlte::page')

@section('title', 'Gestión de Asignación a Evento')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Asignación a Evento</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-plus-circle me-1"></i> Nueva Asignación
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

    {{-- Tabla de asignaciones --}}
    <x-adminlte-card title="Listado de Asignaciones" icon="bi bi-calendar2-check-fill" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblAsignaciones" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Ítem</th>
                        <th>Evento</th>
                        <th>Cantidad</th>
                        <th>Fecha Salida</th>
                        <th>Fecha Retorno</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th>Condición</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $asig)
                        <tr>
                            <td class="text-center">{{ $asig['COD_ASIGNACION'] ?? '—' }}</td>
                            <td>{{ $asig['NOM_ITEM'] ?? $asig['COD_ITEM'] ?? '—' }}</td>
                            <td class="text-center">{{ $asig['COD_EVENTO'] ?? '—' }}</td>
                            <td class="text-center">{{ $asig['CAN_ASIGNADA'] ?? '—' }}</td>
                            <td class="text-center">
                                {{ isset($asig['FEC_SALIDA']) ? substr($asig['FEC_SALIDA'], 0, 10) : '—' }}
                            </td>
                            <td class="text-center">
                                {{ isset($asig['FEC_RETORNO']) ? substr($asig['FEC_RETORNO'], 0, 10) : '—' }}
                            </td>
                            <td>{{ $asig['NOM_RESPONSABLE'] ?? '—' }}</td>
                            <td class="text-center">
                                @php $estado = $asig['IND_ESTADO'] ?? 'PENDIENTE'; @endphp
                                @if($estado === 'PENDIENTE')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($estado === 'ENTREGADO')
                                    <span class="badge bg-primary">Entregado</span>
                                @elseif($estado === 'RETORNADO')
                                    <span class="badge bg-success">Retornado</span>
                                @else
                                    <span class="badge bg-danger">Perdido</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php $condicion = $asig['IND_CONDICION'] ?? null; @endphp
                                @if($condicion === 'BUENO')
                                    <span class="badge bg-success">Bueno</span>
                                @elseif($condicion === 'DANIADO')
                                    <span class="badge bg-warning text-dark">Dañado</span>
                                @elseif($condicion === 'PERDIDO')
                                    <span class="badge bg-danger">Perdido</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar{{ $asig['COD_ASIGNACION'] }}"
                                        title="Editar asignación">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Modal editar asignación --}}
                        <div class="modal fade" id="modalEditar{{ $asig['COD_ASIGNACION'] }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form method="POST"
                                      action="{{ route('inventario.asignaciones.update', $asig['COD_ASIGNACION']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Asignación #{{ $asig['COD_ASIGNACION'] }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Cantidad asignada <span class="text-danger">*</span></label>
                                                    <input type="number" name="can_asignada" class="form-control"
                                                           value="{{ $asig['CAN_ASIGNADA'] ?? 1 }}" required min="1">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Fecha de salida <span class="text-danger">*</span></label>
                                                    <input type="datetime-local" name="fec_salida" class="form-control"
                                                           value="{{ isset($asig['FEC_SALIDA']) ? str_replace(' ', 'T', substr($asig['FEC_SALIDA'], 0, 16)) : '' }}"
                                                           required>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Fecha de retorno</label>
                                                    <input type="datetime-local" name="fec_retorno" class="form-control"
                                                           value="{{ isset($asig['FEC_RETORNO']) ? str_replace(' ', 'T', substr($asig['FEC_RETORNO'], 0, 16)) : '' }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                    <select name="ind_estado_asig" class="form-select" required>
                                                        <option value="PENDIENTE"  {{ ($asig['IND_ESTADO'] ?? '') === 'PENDIENTE'  ? 'selected' : '' }}>Pendiente</option>
                                                        <option value="ENTREGADO"  {{ ($asig['IND_ESTADO'] ?? '') === 'ENTREGADO'  ? 'selected' : '' }}>Entregado</option>
                                                        <option value="RETORNADO"  {{ ($asig['IND_ESTADO'] ?? '') === 'RETORNADO'  ? 'selected' : '' }}>Retornado</option>
                                                        <option value="PERDIDO"    {{ ($asig['IND_ESTADO'] ?? '') === 'PERDIDO'    ? 'selected' : '' }}>Perdido</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Condición</label>
                                                    <select name="ind_condicion" class="form-select">
                                                        <option value="">— Sin especificar —</option>
                                                        <option value="BUENO"   {{ ($asig['IND_CONDICION'] ?? '') === 'BUENO'   ? 'selected' : '' }}>Bueno</option>
                                                        <option value="DANIADO" {{ ($asig['IND_CONDICION'] ?? '') === 'DANIADO' ? 'selected' : '' }}>Dañado</option>
                                                        <option value="PERDIDO" {{ ($asig['IND_CONDICION'] ?? '') === 'PERDIDO' ? 'selected' : '' }}>Perdido</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Responsable</label>
                                                    <input type="text" name="nom_resp_asig" class="form-control"
                                                           value="{{ $asig['NOM_RESPONSABLE'] ?? '' }}" maxlength="100">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Observaciones</label>
                                                <textarea name="des_observaciones" class="form-control" rows="2">{{ $asig['DES_OBSERVACIONES'] ?? '' }}</textarea>
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
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                No hay asignaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- Modal crear asignación --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('inventario.asignaciones.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Nueva Asignación a Evento</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ID del Evento <span class="text-danger">*</span></label>
                                <input type="number" name="cod_evento" class="form-control" required min="1"
                                       placeholder="Ej: 1">
                                <small class="text-muted">Pendiente: combo de eventos por nombre</small>
                            </div>
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
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cantidad asignada <span class="text-danger">*</span></label>
                                <input type="number" name="can_asignada" class="form-control" required min="1" value="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Fecha de salida <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="fec_salida" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Fecha de retorno</label>
                                <input type="datetime-local" name="fec_retorno" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre del responsable</label>
                            <input type="text" name="nom_resp_asig" class="form-control" maxlength="100"
                                   placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="des_observaciones" class="form-control" rows="2"
                                      placeholder="Notas adicionales..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar
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
            $('#tblAsignaciones').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                pageLength: 10,
                order: [[0, 'asc']],
            });
        }

        // Select2 para el combo de ítem (buscable)
        if (typeof $.fn.select2 !== 'undefined') {
            $('#modalCrear select[name="cod_item"]').select2({
                dropdownParent: $('#modalCrear'),
                width: '100%'
            });
        }
    });
</script>
@stop