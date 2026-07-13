@extends('adminlte::page')

@section('title', 'Empleados')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Gestión de Empleados</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearEmpleado">
                <i class="bi bi-person-plus-fill me-1"></i> Nuevo Empleado
            </button>
        </div>
    </div>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <x-adminlte-card title="Listado de Empleados" icon="bi bi-briefcase-fill" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblEmpleados" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Persona</th>
                        <th>Cargo</th>
                        <th>Fecha Contratación</th>
                        <th>Salario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleados as $e)
                        <tr>
                            <td class="text-center fw-bold">{{ $e['COD_EMPLEADO'] }}</td>
                            @php
                                $personaRel = collect($personas)->firstWhere('COD_PERSONA', $e['COD_PERSONA']);
                                $nombrePersona = $personaRel ? trim($personaRel['PRIMER_NOMBRE'] . ' ' . $personaRel['APELLIDO']) : 'Desconocido';
                            @endphp
                            <td>{{ $nombrePersona }}</td>
                            <td><span class="badge bg-secondary">{{ $e['CARGO'] }}</span></td>
                            <td class="text-center">{{ $e['FEC_CONTRATACION'] ?? '—' }}</td>
                            <td class="text-end">
                                ${{ number_format($e['SALARIO'] ?? 0, 2) }}
                            </td>
                            <td class="text-center" style="white-space:nowrap;">
                                {{-- Editar --}}
                                <button class="btn btn-warning btn-sm btn-editar-empleado"
                                        title="Editar"
                                        data-id="{{ $e['COD_PERSONA'] }}"
                                        data-cargo="{{ $e['CARGO'] }}"
                                        data-fec="{{ $e['FEC_CONTRATACION'] ?? '' }}"
                                        data-salario="{{ $e['SALARIO'] ?? 0 }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarEmpleado">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                {{-- Soft-Delete --}}
                                <form method="POST"
                                      action="{{ url('empleados/' . $e['COD_PERSONA']) }}"
                                      class="d-inline form-soft-delete">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="accion" value="SOFT_DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Desactivar">
                                        <i class="bi bi-person-slash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay empleados registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- ════════ MODAL: CREAR ════════ --}}
    <div class="modal fade" id="modalCrearEmpleado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-1"></i> Nuevo Empleado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('empleados.store') }}" id="formCrearEmpleado" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Persona <span class="text-danger">*</span></label>
                                <select name="cod_persona" class="form-select" required>
                                    <option value="">— Seleccione —</option>
                                    @foreach($personas as $p)
                                        <option value="{{ $p['COD_PERSONA'] }}">
                                            {{ $p['PRIMER_NOMBRE'] }} {{ $p['APELLIDO'] }} ({{ $p['DNI'] }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Seleccione una persona.</div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Cargo <span class="text-danger">*</span></label>
                                <input type="text" name="cargo" class="form-control" maxlength="100"
                                       required placeholder="Ej. Gerente de Ventas">
                                <div class="invalid-feedback">El cargo es obligatorio.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha de Contratación <span class="text-danger">*</span></label>
                                <input type="date" name="fec_contratacion" class="form-control" required>
                                <div class="invalid-feedback">Ingrese la fecha de contratación.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Salario <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="salario" class="form-control" min="0"
                                           step="0.01" required placeholder="0.00">
                                    <div class="invalid-feedback">Ingrese el salario.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════ MODAL: EDITAR ════════ --}}
    <div class="modal fade" id="modalEditarEmpleado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-1"></i> Editar Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="" id="formEditarEmpleado" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Cargo <span class="text-danger">*</span></label>
                                <input type="text" id="editCargo" name="cargo" class="form-control"
                                       maxlength="100" required>
                                <div class="invalid-feedback">El cargo es obligatorio.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha de Contratación <span class="text-danger">*</span></label>
                                <input type="date" id="editFecContratacion" name="fec_contratacion"
                                       class="form-control" required>
                                <div class="invalid-feedback">Ingrese la fecha de contratación.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Salario <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" id="editSalario" name="salario" class="form-control"
                                           min="0" step="0.01" required>
                                    <div class="invalid-feedback">Ingrese el salario.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save me-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('plugins.Datatables', true)

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof DataTable !== 'undefined') {
        new DataTable('#tblEmpleados', {
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            order: [[0, 'desc']],
        });
    }

    const formCrear = document.getElementById('formCrearEmpleado');
    formCrear.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    const formEditar = document.getElementById('formEditarEmpleado');
    formEditar.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    document.querySelectorAll('.btn-editar-empleado').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editCargo').value           = this.dataset.cargo;
            document.getElementById('editFecContratacion').value = this.dataset.fec;
            document.getElementById('editSalario').value         = this.dataset.salario;
            document.getElementById('formEditarEmpleado').action =
                '{{ url("empleados") }}/' + this.dataset.id;
        });
    });

    document.querySelectorAll('.form-soft-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('¿Desactivar este empleado del sistema?')) { e.preventDefault(); }
        });
    });

});
</script>
@endpush
