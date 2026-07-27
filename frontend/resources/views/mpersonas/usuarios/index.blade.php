@extends('adminlte::page')

@section('title', 'Usuarios del Sistema')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Usuarios del Sistema</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
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

    <x-adminlte-card title="Listado de Usuarios" icon="bi bi-person-check-fill" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblUsuarios" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nombre Usuario</th>
                        <th>Persona</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Primer Ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td class="text-center fw-bold">{{ $u['COD_USUARIO'] }}</td>
                            <td><span class="badge bg-dark">{{ $u['NOMBRE'] }}</span></td>
                            @php
                                $personaRel = collect($personas)->firstWhere('COD_PERSONA', $u['COD_PERSONA']);
                                $nombrePersona = $personaRel ? trim($personaRel['PRIMER_NOMBRE'] . ' ' . $personaRel['APELLIDO']) : 'Desconocido';
                                $tipoRel = collect($tiposUsuario)->firstWhere('COD_TIPO_USR', $u['COD_TIPO_USR']);
                                $nombreTipo = $tipoRel ? $tipoRel['NOM_TIPO'] : '—';
                            @endphp
                            <td>{{ $nombrePersona }}</td>
                            <td>{{ $nombreTipo }}</td>
                            <td class="text-center">
                                @if(($u['IND_USR'] ?? '1') == '1')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($u['IND_PRIMER_ING'] ?? '0') == '1')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @else
                                    <span class="badge bg-success">Completado</span>
                                @endif
                            </td>
                            <td class="text-center" style="white-space:nowrap;">
                                {{-- Editar --}}
                                <button class="btn btn-warning btn-sm btn-editar-usuario"
                                        title="Editar"
                                        data-id="{{ $u['COD_PERSONA'] }}"
                                        data-nombre="{{ $u['NOMBRE'] }}"
                                        data-cod-tipo="{{ $u['COD_TIPO_USR'] }}"
                                        data-ind-usr="{{ $u['IND_USR'] ?? '1' }}"
                                        data-ind-primer="{{ $u['IND_PRIMER_ING'] ?? '1' }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditarUsuario">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                {{-- Soft-Delete --}}
                                <form method="POST"
                                      action="{{ url('usuarios/' . $u['COD_PERSONA']) }}"
                                      class="d-inline form-soft-delete">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="accion" value="SOFT_DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Desactivar">
                                        <i class="bi bi-person-dash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- ════════ MODAL: CREAR ════════ --}}
    <div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('usuarios.store') }}" id="formCrearUsuario" novalidate>
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
                                <label class="form-label fw-semibold">Tipo de Usuario <span class="text-danger">*</span></label>
                                <select name="cod_tipo_usr" class="form-select" required>
                                    <option value="">— Seleccione —</option>
                                    @foreach($tiposUsuario as $t)
                                        <option value="{{ $t['COD_TIPO_USR'] }}">{{ $t['NOM_TIPO'] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Seleccione un tipo de usuario.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre de Usuario <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" class="form-control" maxlength="255" required
                                       placeholder="Ej. Cirst12">
                                <div class="invalid-feedback">El nombre de usuario es obligatorio.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contraseña <span class="text-danger">*</span></label>
                                <input type="password" name="clave" class="form-control" minlength="6" maxlength="2000"
                                       required placeholder="Mínimo 6 caracteres">
                                <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Token (6 dígitos) <span class="text-danger">*</span></label>
                                <input type="text" name="token" class="form-control" minlength="6" maxlength="6"
                                       required placeholder="Ej. 1D2F34" pattern="[A-Za-z0-9]{6}">
                                <div class="invalid-feedback">El token debe tener exactamente 6 caracteres.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                                <select name="ind_usr" class="form-select" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Primer Ingreso <span class="text-danger">*</span></label>
                                <select name="ind_primer_ing" class="form-select" required>
                                    <option value="1">Sí (pendiente)</option>
                                    <option value="0">No (completado)</option>
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

    {{-- ════════ MODAL: EDITAR ════════ --}}
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-1"></i> Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="" id="formEditarUsuario" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipo de Usuario <span class="text-danger">*</span></label>
                                <select id="editCodTipoUsr" name="cod_tipo_usr" class="form-select" required>
                                    @foreach($tiposUsuario as $t)
                                        <option value="{{ $t['COD_TIPO_USR'] }}">{{ $t['NOM_TIPO'] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Seleccione un tipo de usuario.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre de Usuario <span class="text-danger">*</span></label>
                                <input type="text" id="editNombreUsr" name="nombre" class="form-control"
                                       maxlength="255" required>
                                <div class="invalid-feedback">El nombre de usuario es obligatorio.</div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nueva Contraseña
                                    <small class="text-muted fw-normal">(dejar vacío para no cambiar)</small>
                                </label>
                                <input type="password" name="clave" class="form-control"
                                       minlength="6" maxlength="2000" placeholder="Mínimo 6 caracteres">
                                <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                                <select id="editIndUsr" name="ind_usr" class="form-select" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Primer Ingreso <span class="text-danger">*</span></label>
                                <select id="editIndPrimerIng" name="ind_primer_ing" class="form-select" required>
                                    <option value="1">Sí (pendiente)</option>
                                    <option value="0">No (completado)</option>
                                </select>
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
        new DataTable('#tblUsuarios', {
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            order: [[0, 'desc']],
        });
    }

    // ── Validación Crear
    const formCrear = document.getElementById('formCrearUsuario');
    formCrear.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    // ── Validación Editar
    const formEditar = document.getElementById('formEditarUsuario');
    formEditar.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    // ── Cargar datos en Modal Editar
    document.querySelectorAll('.btn-editar-usuario').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('editCodTipoUsr').value    = this.dataset.codTipo;
            document.getElementById('editNombreUsr').value     = this.dataset.nombre;
            document.getElementById('editIndUsr').value        = this.dataset.indUsr;
            document.getElementById('editIndPrimerIng').value  = this.dataset.indPrimer;
            document.getElementById('formEditarUsuario').action =
                '{{ url("usuarios") }}/' + this.dataset.id;
        });
    });

    // ── Confirmar Soft-Delete
    document.querySelectorAll('.form-soft-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('¿Desactivar este usuario? No podrá acceder al sistema.')) {
                e.preventDefault();
            }
        });
    });

});
</script>
@endpush
