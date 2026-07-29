@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0"><i class="bi bi-person-heart me-2"></i>Gestión de Clientes</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearCliente">
                <i class="bi bi-person-plus-fill me-1"></i> Nuevo Cliente
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

    <x-adminlte-card title="Listado de Clientes" icon="bi bi-person-heart" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblClientes" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Persona</th>
                        <th>Tipo de Cliente</th>
                        <th>Empresa</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $c)
                        <tr>
                            <td class="text-center fw-bold">{{ $c['COD_CLIENTE'] }}</td>
                            @php
                                $personaRel = collect($personas)->firstWhere('COD_PERSONA', $c['COD_PERSONA']);
                                $nombrePersona = $personaRel ? trim($personaRel['PRIMER_NOMBRE'] . ' ' . $personaRel['APELLIDO']) : 'Desconocido';
                                $tipoRel = collect($tiposCliente)->firstWhere('COD_TIPO_CLI', $c['COD_TIPO_CLI']);
                                $nombreTipo = $tipoRel ? $tipoRel['NOM_TIPO_CLI'] : '—';
                            @endphp
                            <td>{{ $nombrePersona }}</td>
                            <td><span class="badge bg-info text-dark">{{ $nombreTipo }}</span></td>
                            <td>{{ $c['NOM_EMPRESA'] ?? '—' }}</td>
                            <td class="text-center">
                                @if(($c['IND_CLIENTE'] ?? '1') == '1')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center" style="white-space:nowrap;">
                                {{-- Editar --}}
                                <button class="btn btn-warning btn-sm btn-editar-cliente"
                                        title="Editar"
                                        data-id="{{ $c['COD_PERSONA'] }}"
                                        data-cod-tipo="{{ $c['COD_TIPO_CLI'] }}"
                                        data-empresa="{{ $c['NOM_EMPRESA'] ?? '' }}"
                                        data-ind="{{ $c['IND_CLIENTE'] ?? '1' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarCliente">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                {{-- Soft-Delete --}}
                                <button type="button" class="btn btn-danger btn-sm btn-desactivar-cliente" title="Desactivar"
                                        data-id="{{ $c['COD_PERSONA'] }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDesactivarCliente">
                                    <i class="bi bi-person-slash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- MODAL: CREAR --}}
    <div class="modal fade" id="modalCrearCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-1"></i> Nuevo Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('clientes.store') }}" id="formCrearCliente" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
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
                                <label class="form-label fw-semibold">Tipo de Cliente <span class="text-danger">*</span></label>
                                <select name="cod_tipo_cli" class="form-select" required>
                                    <option value="">— Seleccione —</option>
                                    @foreach($tiposCliente as $t)
                                        <option value="{{ $t['COD_TIPO_CLI'] }}">{{ $t['NOM_TIPO_CLI'] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Seleccione un tipo de cliente.</div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Empresa</label>
                                <input type="text" name="nom_empresa" class="form-control" maxlength="255"
                                       placeholder="Nombre de la empresa (opcional)">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                                <select name="ind_cliente" class="form-select" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
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

    {{-- MODAL: EDITAR --}}
    <div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-1"></i> Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="" id="formEditarCliente" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipo de Cliente <span class="text-danger">*</span></label>
                                <select id="editCodTipoCli" name="cod_tipo_cli" class="form-select" required>
                                    @foreach($tiposCliente as $t)
                                        <option value="{{ $t['COD_TIPO_CLI'] }}">{{ $t['NOM_TIPO_CLI'] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Seleccione un tipo de cliente.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                                <select id="editIndCliente" name="ind_cliente" class="form-select" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Empresa</label>
                                <input type="text" id="editEmpresaCli" name="nom_empresa" class="form-control"
                                       maxlength="255" placeholder="Nombre de la empresa (opcional)">
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

    {{-- MODAL: DESACTIVAR --}}
    <div class="modal fade" id="modalDesactivarCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-1"></i> Desactivar Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="" id="formDesactivarCliente">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="accion" value="SOFT_DELETE">
                    <div class="modal-body">
                        <p class="mb-0 fs-5 text-center">¿Está seguro que desea desactivar a este cliente del sistema?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-check-circle-fill me-1"></i> Sí, desactivar
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
        new DataTable('#tblClientes', {
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            order: [[0, 'desc']],
        });
    }

    const formCrear = document.getElementById('formCrearCliente');
    formCrear.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    const formEditar = document.getElementById('formEditarCliente');
    formEditar.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    document.querySelectorAll('.btn-editar-cliente').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editCodTipoCli').value  = this.dataset.codTipo;
            document.getElementById('editEmpresaCli').value  = this.dataset.empresa;
            document.getElementById('editIndCliente').value  = this.dataset.ind;
            document.getElementById('formEditarCliente').action =
                '{{ url("clientes") }}/' + this.dataset.id;
        });
    });

    document.querySelectorAll('.btn-desactivar-cliente').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('formDesactivarCliente').action =
                '{{ url("clientes") }}/' + this.dataset.id;
        });
    });

});
</script>
@endpush
