@extends('adminlte::page')

@section('title', 'Gestión de Reservaciones')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Reservaciones</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-calendar-plus me-1"></i> Nueva Reservación
            </button>
        </div>
    </div>
@stop

@section('content')

    {{-- Alertas --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
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
    <x-adminlte-card title="Listado de Reservaciones" icon="bi bi-calendar-check" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblReservaciones" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Espacio</th>
                        <th>Cliente</th>
                        <th>Empleado</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservaciones as $r)
                        <tr>
                            <td class="text-center">{{ $r['COD_RESERVACION'] }}</td>
                            <td>{{ $r['COD_ESPACIO'] }}</td> {{-- Se podría cruzar con $espacios para mostrar nombre --}}
                            <td>{{ $r['COD_CLIENTE'] }}</td>
                            <td>{{ $r['COD_EMPLEADO'] }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($r['FEC_INICIO'])->setTimezone('America/Costa_Rica')->format('d/m/Y H:i') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($r['FEC_FIN'])->setTimezone('America/Costa_Rica')->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                @if($r['IND_ESTADO'] === 'PENDIENTE')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($r['IND_ESTADO'] === 'CONFIRMADA')
                                    <span class="badge bg-info">Confirmada</span>
                                @elseif($r['IND_ESTADO'] === 'COMPLETADA')
                                    <span class="badge bg-success">Completada</span>
                                @else
                                    <span class="badge bg-danger">Cancelada</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <button class="btn btn-warning btn-xs"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-id="{{ $r['COD_RESERVACION'] }}"
                                    data-espacio="{{ $r['COD_ESPACIO'] }}"
                                    data-cliente="{{ $r['COD_CLIENTE'] }}"
                                    data-empleado="{{ $r['COD_EMPLEADO'] }}"
                                    data-inicio="{{ \Carbon\Carbon::parse($r['FEC_INICIO'])->setTimezone('America/Costa_Rica')->format('Y-m-d\TH:i') }}"
                                    data-fin="{{ \Carbon\Carbon::parse($r['FEC_FIN'])->setTimezone('America/Costa_Rica')->format('Y-m-d\TH:i') }}"
                                    data-estado="{{ $r['IND_ESTADO'] }}"
                                    data-notas="{{ $r['DES_NOTAS'] ?? '' }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                
                                @if($r['IND_ESTADO'] === 'PENDIENTE')
                                    <button class="btn btn-info btn-xs"
                                        title="Confirmar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCambioEstado"
                                        data-id="{{ $r['COD_RESERVACION'] }}"
                                        data-accion="CONFIRMAR">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                @endif
                                
                                @if($r['IND_ESTADO'] === 'CONFIRMADA')
                                    <button class="btn btn-success btn-xs"
                                        title="Completar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCambioEstado"
                                        data-id="{{ $r['COD_RESERVACION'] }}"
                                        data-accion="COMPLETAR">
                                        <i class="bi bi-check2-all"></i>
                                    </button>
                                @endif
                                
                                @if($r['IND_ESTADO'] !== 'CANCELADA' && $r['IND_ESTADO'] !== 'COMPLETADA')
                                    <button class="btn btn-danger btn-xs"
                                        title="Cancelar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCambioEstado"
                                        data-id="{{ $r['COD_RESERVACION'] }}"
                                        data-accion="CANCELAR">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No hay reservaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- MODAL CREAR --}}
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-calendar-plus me-1"></i> Nueva Reservación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('reservaciones.store') }}" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Espacio <span class="text-danger">*</span></label>
                                <select name="cod_espacio" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($espacios as $e)
                                        @if($e['IND_ESTADO'] !== 'INACTIVO')
                                            <option value="{{ $e['COD_ESPACIO'] }}">{{ $e['NOM_ESPACIO'] }} (Cap: {{ $e['CAN_CAPACIDAD'] }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
                                <select name="cod_cliente" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c['COD_CLIENTE'] }}">{{ $c['NOM_EMPRESA_CLI'] ?? 'Cliente #'.$c['COD_CLIENTE'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Empleado Asignado <span class="text-danger">*</span></label>
                                <select name="cod_empleado" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($empleados as $em)
                                        <option value="{{ $em['COD_EMPLEADO'] }}">{{ 'Empleado #'.$em['COD_EMPLEADO'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" name="fec_inicio_date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Hora Inicio <span class="text-danger">*</span></label>
                                <input type="time" name="fec_inicio_time" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="date" name="fec_fin_date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Hora Fin <span class="text-danger">*</span></label>
                                <input type="time" name="fec_fin_time" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notas Adicionales</label>
                                <textarea name="des_notas" class="form-control" rows="3"></textarea>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil me-1"></i> Editar Reservación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEditar" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Espacio <span class="text-danger">*</span></label>
                                <select name="cod_espacio" id="edit_espacio" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($espacios as $e)
                                        <option value="{{ $e['COD_ESPACIO'] }}">{{ $e['NOM_ESPACIO'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cliente <span class="text-danger">*</span></label>
                                <select name="cod_cliente" id="edit_cliente" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c['COD_CLIENTE'] }}">{{ $c['NOM_EMPRESA_CLI'] ?? 'Cliente #'.$c['COD_CLIENTE'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Empleado Asignado <span class="text-danger">*</span></label>
                                <select name="cod_empleado" id="edit_empleado" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($empleados as $em)
                                        <option value="{{ $em['COD_EMPLEADO'] }}">{{ 'Empleado #'.$em['COD_EMPLEADO'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                                <select name="ind_estado" id="edit_estado" class="form-select" required>
                                    <option value="PENDIENTE">Pendiente</option>
                                    <option value="CONFIRMADA">Confirmada</option>
                                    <option value="CANCELADA">Cancelada</option>
                                    <option value="COMPLETADA">Completada</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" name="fec_inicio_date" id="edit_inicio_date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Hora Inicio <span class="text-danger">*</span></label>
                                <input type="time" name="fec_inicio_time" id="edit_inicio_time" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="date" name="fec_fin_date" id="edit_fin_date" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Hora Fin <span class="text-danger">*</span></label>
                                <input type="time" name="fec_fin_time" id="edit_fin_time" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notas Adicionales</label>
                                <textarea name="des_notas" id="edit_notas" class="form-control" rows="3"></textarea>
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

    {{-- MODAL CAMBIO ESTADO --}}
    <div class="modal fade" id="modalCambioEstado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-circle me-1"></i> Confirmar Acción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea <strong id="accionLabel"></strong> esta reservación?</p>
                </div>
                <form method="POST" id="formCambioEstado">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="accion" id="inputAccion">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info text-white" id="btnAccion">Confirmar</button>
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
            document.getElementById('edit_espacio').value = btn.getAttribute('data-espacio');
            document.getElementById('edit_cliente').value = btn.getAttribute('data-cliente');
            document.getElementById('edit_empleado').value = btn.getAttribute('data-empleado');
            document.getElementById('edit_estado').value = btn.getAttribute('data-estado');
            var inicio = btn.getAttribute('data-inicio');
            if(inicio && inicio.includes('T')) {
                var partesInicio = inicio.split('T');
                document.getElementById('edit_inicio_date').value = partesInicio[0];
                document.getElementById('edit_inicio_time').value = partesInicio[1];
            }
            var fin = btn.getAttribute('data-fin');
            if(fin && fin.includes('T')) {
                var partesFin = fin.split('T');
                document.getElementById('edit_fin_date').value = partesFin[0];
                document.getElementById('edit_fin_time').value = partesFin[1];
            }
            document.getElementById('edit_notas').value = btn.getAttribute('data-notas');
            document.getElementById('formEditar').action = '/reservaciones/' + btn.getAttribute('data-id');
        });
    }

    var modalCambioEstado = document.getElementById('modalCambioEstado');
    if (modalCambioEstado) {
        modalCambioEstado.addEventListener('show.bs.modal', function (event) {
            var btn = event.relatedTarget;
            var accion = btn.getAttribute('data-accion');
            document.getElementById('accionLabel').textContent = accion.toLowerCase();
            document.getElementById('inputAccion').value = accion;
            document.getElementById('formCambioEstado').action = '/reservaciones/' + btn.getAttribute('data-id');
            
            var btnSubmit = document.getElementById('btnAccion');
            btnSubmit.className = 'btn text-white ' + (accion === 'CANCELAR' ? 'btn-danger' : (accion === 'CONFIRMAR' ? 'btn-info' : 'btn-success'));
            btnSubmit.textContent = accion;
        });
    }
</script>
@endpush
