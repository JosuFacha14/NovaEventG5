@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0"><i class="bi bi-truck me-2"></i>Gestión de Proveedores</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearProveedor">
                <i class="bi bi-plus-circle-fill me-1"></i> Nuevo Proveedor
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
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <x-adminlte-card title="Listado de Proveedores" icon="bi bi-truck" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblProveedores" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Persona</th>
                        <th>Empresa</th>
                        <th>Categoría de Servicio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $pv)
                        <tr>
                            <td class="text-center fw-bold">{{ $pv['COD_PROVEEDOR'] }}</td>
                            @php
                                $personaRel = collect($personas)->firstWhere('COD_PERSONA', $pv['COD_PERSONA']);
                                $nombrePersona = $personaRel ? trim($personaRel['PRIMER_NOMBRE'] . ' ' . $personaRel['APELLIDO']) : 'Desconocido';
                            @endphp
                            <td>{{ $nombrePersona }}</td>
                            <td><span class="badge bg-dark">{{ $pv['EMPRESA'] }}</span></td>
                            <td>{{ $pv['CATEGORIA_SERVICIO'] ?? '—' }}</td>
                            <td class="text-center" style="white-space:nowrap;">
                                {{-- Editar --}}
                                <button class="btn btn-warning btn-sm btn-editar-proveedor"
                                        title="Editar"
                                        data-id="{{ $pv['COD_PERSONA'] }}"
                                        data-empresa="{{ $pv['EMPRESA'] }}"
                                        data-categoria="{{ $pv['CATEGORIA_SERVICIO'] ?? '' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarProveedor">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                {{-- Soft-Delete --}}
                                <form method="POST"
                                      action="{{ url('proveedores/' . $pv['COD_PERSONA']) }}"
                                      class="d-inline form-soft-delete">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="accion" value="SOFT_DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Desactivar">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay proveedores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- ════════ MODAL: CREAR ════════ --}}
    <div class="modal fade" id="modalCrearProveedor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-1"></i> Nuevo Proveedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('proveedores.store') }}" id="formCrearProveedor" novalidate>
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
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Empresa <span class="text-danger">*</span></label>
                                <input type="text" name="empresa" class="form-control" maxlength="150"
                                       required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" placeholder="Nombre de la empresa proveedora">
                                <div class="invalid-feedback">la empresa solo pueden ser letras</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categoría de Servicio</label>
                                <input type="text" name="categoria_servicio" class="form-control" maxlength="100"
                                       pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" placeholder="Ej. Catering, Audio y Video…">
                                <div class="invalid-feedback">la categoria de servicio solo pueden ser letras</div>
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
    <div class="modal fade" id="modalEditarProveedor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-1"></i> Editar Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="" id="formEditarProveedor" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Empresa <span class="text-danger">*</span></label>
                                <input type="text" id="editEmpresaProv" name="empresa" class="form-control"
                                       maxlength="150" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$">
                                <div class="invalid-feedback">la empresa solo pueden ser letras</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categoría de Servicio</label>
                                <input type="text" id="editCategoriaProv" name="categoria_servicio"
                                       class="form-control" maxlength="100" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$">
                                <div class="invalid-feedback">la categoria de servicio solo pueden ser letras</div>
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
        new DataTable('#tblProveedores', {
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            order: [[0, 'desc']],
        });
    }

    const formCrear = document.getElementById('formCrearProveedor');
    formCrear.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    const formEditar = document.getElementById('formEditarProveedor');
    formEditar.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    document.querySelectorAll('.btn-editar-proveedor').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editEmpresaProv').value    = this.dataset.empresa;
            document.getElementById('editCategoriaProv').value  = this.dataset.categoria;
            document.getElementById('formEditarProveedor').action =
                '{{ url("proveedores") }}/' + this.dataset.id;
        });
    });

    document.querySelectorAll('.form-soft-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('¿Desactivar este proveedor del sistema?')) { e.preventDefault(); }
        });
    });

});
</script>
@endpush
