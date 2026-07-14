@extends('adminlte::page')

@section('title', 'Bloqueos de Espacios')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Bloqueos de Espacios</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-lock-fill me-1"></i> Bloquear Espacio
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
    <x-adminlte-card title="Listado de Espacios Ocupados/Bloqueados" icon="bi bi-shield-lock" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblOcupados" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Espacio</th>
                        <th>Inicio Bloqueo</th>
                        <th>Fin Bloqueo</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ocupados as $o)
                        <tr>
                            <td class="text-center">{{ $o['COD_ESPA_OCUP'] }}</td>
                            <td>{{ $o['COD_ESPACIO'] }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($o['FEC_INICIO'])->setTimezone('America/Costa_Rica')->format('d/m/Y H:i') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($o['FEC_FIN'])->setTimezone('America/Costa_Rica')->format('d/m/Y H:i') }}</td>
                            <td>{{ $o['DES_MOTIVO'] }}</td>
                            <td class="text-center text-nowrap">
                                <button class="btn btn-warning btn-xs"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-id="{{ $o['COD_ESPA_OCUP'] }}"
                                    data-espacio="{{ $o['COD_ESPACIO'] }}"
                                    data-inicio="{{ \Carbon\Carbon::parse($o['FEC_INICIO'])->setTimezone('America/Costa_Rica')->format('Y-m-d\TH:i') }}"
                                    data-fin="{{ \Carbon\Carbon::parse($o['FEC_FIN'])->setTimezone('America/Costa_Rica')->format('Y-m-d\TH:i') }}"
                                    data-motivo="{{ $o['DES_MOTIVO'] }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay bloqueos registrados.</td>
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
                    <h5 class="modal-title"><i class="bi bi-lock-fill me-1"></i> Registrar Bloqueo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('espacios_ocupados.store') }}" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Espacio <span class="text-danger">*</span></label>
                                <select name="cod_espacio" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($espacios as $e)
                                        <option value="{{ $e['COD_ESPACIO'] }}">{{ $e['NOM_ESPACIO'] }}</option>
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
                                <label class="form-label fw-semibold">Motivo</label>
                                <input type="text" name="des_motivo" class="form-control" maxlength="200">
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
                    <h5 class="modal-title"><i class="bi bi-pencil me-1"></i> Editar Bloqueo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEditar" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Espacio <span class="text-danger">*</span></label>
                                <select name="cod_espacio" id="edit_espacio" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($espacios as $e)
                                        <option value="{{ $e['COD_ESPACIO'] }}">{{ $e['NOM_ESPACIO'] }}</option>
                                    @endforeach
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
                                <label class="form-label fw-semibold">Motivo</label>
                                <input type="text" name="des_motivo" id="edit_motivo" class="form-control" maxlength="200">
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

@stop

@section('plugins.Datatables', true)

@push('js')
<script>
    var modalEditar = document.getElementById('modalEditar');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function (event) {
            var btn = event.relatedTarget;
            document.getElementById('edit_espacio').value = btn.getAttribute('data-espacio');
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
            document.getElementById('edit_motivo').value = btn.getAttribute('data-motivo');
            document.getElementById('formEditar').action = '/espacios-ocupados/' + btn.getAttribute('data-id');
        });
    }
</script>
@endpush
