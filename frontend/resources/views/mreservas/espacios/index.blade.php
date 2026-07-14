@extends('adminlte::page')

@section('title', 'Gestión de Espacios')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Espacios</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Espacio
            </button>
        </div>
    </div>
@stop

@section('content')

    {{-- Alertas --}}
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

    {{-- Tabla --}}
    <x-adminlte-card title="Listado de Espacios" icon="bi bi-building" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblEspacios" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Precio/Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($espacios as $e)
                        <tr>
                            <td class="text-center">{{ $e['COD_ESPACIO'] }}</td>
                            <td>{{ $e['NOM_ESPACIO'] }}</td>
                            <td class="text-center">{{ $e['CAN_CAPACIDAD'] }}</td>
                            <td class="text-center">{{ $e['TIP_ESPACIO'] }}</td>
                            <td class="text-center">
                                @if($e['IND_ESTADO'] === 'DISPONIBLE')
                                    <span class="badge bg-success">Disponible</span>
                                @elseif($e['IND_ESTADO'] === 'MANTENIMIENTO')
                                    <span class="badge bg-warning text-dark">Mantenimiento</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end">${{ number_format($e['MON_PRECIO_HORA'], 2) }}</td>
                            <td class="text-center text-nowrap">
                                <button class="btn btn-warning btn-xs"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-id="{{ $e['COD_ESPACIO'] }}"
                                    data-nombre="{{ $e['NOM_ESPACIO'] }}"
                                    data-capacidad="{{ $e['CAN_CAPACIDAD'] }}"
                                    data-tipo="{{ $e['TIP_ESPACIO'] }}"
                                    data-estado="{{ $e['IND_ESTADO'] }}"
                                    data-precio="{{ $e['MON_PRECIO_HORA'] }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                @if($e['IND_ESTADO'] !== 'INACTIVO')
                                    <button class="btn btn-danger btn-xs"
                                        title="Dar de baja"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalBaja"
                                        data-id="{{ $e['COD_ESPACIO'] }}"
                                        data-nombre="{{ $e['NOM_ESPACIO'] }}">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay espacios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- MODAL CREAR --}}
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Nuevo Espacio</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('espacios.store') }}" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nombre del Espacio <span class="text-danger">*</span></label>
                                <input type="text" name="nom_espacio" class="form-control" required maxlength="100">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Capacidad <span class="text-danger">*</span></label>
                                <input type="number" name="can_capacidad" class="form-control" required min="1">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Precio por Hora <span class="text-danger">*</span></label>
                                <input type="number" name="mon_precio_hora" class="form-control" required min="0" step="0.01">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tipo de Espacio <span class="text-danger">*</span></label>
                                <select name="tip_espacio" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="SALON">Salón</option>
                                    <option value="AUDITORIO">Auditorio</option>
                                    <option value="AREA_EXTERIOR">Área Exterior</option>
                                    <option value="SALA_REUNION">Sala de Reunión</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDITAR --}}
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil me-1"></i> Editar Espacio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEditar" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nombre del Espacio <span class="text-danger">*</span></label>
                                <input type="text" name="nom_espacio" id="edit_nombre" class="form-control" required maxlength="100">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Capacidad <span class="text-danger">*</span></label>
                                <input type="number" name="can_capacidad" id="edit_capacidad" class="form-control" required min="1">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Precio por Hora <span class="text-danger">*</span></label>
                                <input type="number" name="mon_precio_hora" id="edit_precio" class="form-control" required min="0" step="0.01">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tipo de Espacio <span class="text-danger">*</span></label>
                                <select name="tip_espacio" id="edit_tipo" class="form-select" required>
                                    <option value="SALON">Salón</option>
                                    <option value="AUDITORIO">Auditorio</option>
                                    <option value="AREA_EXTERIOR">Área Exterior</option>
                                    <option value="SALA_REUNION">Sala de Reunión</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                                <select name="ind_estado" id="edit_estado" class="form-select" required>
                                    <option value="DISPONIBLE">Disponible</option>
                                    <option value="MANTENIMIENTO">Mantenimiento</option>
                                    <option value="INACTIVO">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL BAJA --}}
    <div class="modal fade" id="modalBaja" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i> Confirmar baja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea inactivar el espacio <strong id="bajaLabel"></strong>?</p>
                </div>
                <form method="POST" id="formBaja">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="accion" value="SOFT_DELETE">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Inactivar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('plugins.Datatables', true)

@push('js')
<script>
    var modalEditar = document.getElementById('modalEditar');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function (event) {
            var btn = event.relatedTarget;
            document.getElementById('edit_nombre').value = btn.getAttribute('data-nombre');
            document.getElementById('edit_capacidad').value = btn.getAttribute('data-capacidad');
            document.getElementById('edit_precio').value = btn.getAttribute('data-precio');
            document.getElementById('edit_tipo').value = btn.getAttribute('data-tipo');
            document.getElementById('edit_estado').value = btn.getAttribute('data-estado');
            document.getElementById('formEditar').action = '/espacios/' + btn.getAttribute('data-id');
        });
    }

    var modalBaja = document.getElementById('modalBaja');
    if (modalBaja) {
        modalBaja.addEventListener('show.bs.modal', function (event) {
            var btn = event.relatedTarget;
            document.getElementById('bajaLabel').textContent = btn.getAttribute('data-nombre');
            document.getElementById('formBaja').action = '/espacios/' + btn.getAttribute('data-id');
        });
    }
</script>
@endpush
