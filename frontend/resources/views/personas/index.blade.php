@extends('adminlte::page')

@section('title', 'Gestión de Personas')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Personas</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-person-plus me-1"></i> Nueva Persona
            </button>
        </div>
    </div>
@stop

@section('content')

    {{-- ── Alertas de sesión ── --}}
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

    {{-- ── Tabla de personas ── --}}
    <x-adminlte-card title="Listado de Personas" icon="bi bi-people" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblPersonas" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Sexo</th>
                        <th>Estado Civil</th>
                        <th>Edad</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personas as $p)
                        <tr>
                            <td class="text-center">{{ $p['COD_PERSONA'] }}</td>
                            <td>{{ $p['DNI'] }}</td>
                            <td>{{ trim(($p['PRIMER_NOMBRE'] ?? '') . ' ' . ($p['SEGUNDO_NOMBRE'] ?? '')) }}</td>
                            <td>{{ $p['APELLIDO'] }}</td>
                            <td class="text-center">
                                @switch($p['SEXO'])
                                    @case('M') Masculino @break
                                    @case('F') Femenino  @break
                                    @case('O') Otro      @break
                                    @default   No Dice
                                @endswitch
                            </td>
                            <td class="text-center">
                                @switch($p['EST_CIVIL'])
                                    @case('S') Soltero/a  @break
                                    @case('C') Casado/a   @break
                                    @case('V') Viudo/a    @break
                                    @default   —
                                @endswitch
                            </td>
                            <td class="text-center">{{ $p['EDAD'] }}</td>
                            <td class="text-center">{{ $p['TIP_PERSONA'] === 'N' ? 'Natural' : 'Jurídica' }}</td>
                            <td class="text-center text-nowrap">
                                {{-- Ver perfil --}}
                                <a href="{{ route('personas.show', $p['COD_PERSONA']) }}"
                                    class="btn btn-info btn-xs" title="Ver perfil">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Editar --}}
                                <button class="btn btn-warning btn-xs"
                                    title="Editar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-id="{{ $p['COD_PERSONA'] }}"
                                    data-dni="{{ $p['DNI'] }}"
                                    data-primer_nombre="{{ $p['PRIMER_NOMBRE'] }}"
                                    data-segundo_nombre="{{ $p['SEGUNDO_NOMBRE'] ?? '' }}"
                                    data-apellido="{{ $p['APELLIDO'] }}"
                                    data-sexo="{{ $p['SEXO'] }}"
                                    data-est_civil="{{ $p['EST_CIVIL'] }}"
                                    data-edad="{{ $p['EDAD'] }}"
                                    data-tip_persona="{{ $p['TIP_PERSONA'] }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                {{-- Baja lógica --}}
                                <button class="btn btn-danger btn-xs"
                                    title="Dar de baja"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBaja"
                                    data-id="{{ $p['COD_PERSONA'] }}"
                                    data-nombre="{{ $p['PRIMER_NOMBRE'] }} {{ $p['APELLIDO'] }}">
                                    <i class="bi bi-person-x"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay personas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- ════════════════════════════════════════════════════════════
         MODAL: CREAR PERSONA
    ════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCrearLabel">
                        <i class="bi bi-person-plus me-1"></i> Nueva Persona
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('personas.store') }}" id="formCrear" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">DNI <span class="text-danger">*</span></label>
                                <input type="text" name="dni" class="form-control" maxlength="255" required>
                                <div class="invalid-feedback">El DNI es obligatorio.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Primer Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="primer_nombre" class="form-control" maxlength="255" required>
                                <div class="invalid-feedback">El primer nombre es obligatorio.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="form-control" maxlength="255">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido" class="form-control" maxlength="255" required>
                                <div class="invalid-feedback">El apellido es obligatorio.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Edad <span class="text-danger">*</span></label>
                                <input type="number" name="edad" class="form-control" min="0" max="127" required>
                                <div class="invalid-feedback">La edad es obligatoria (0-127).</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo Persona <span class="text-danger">*</span></label>
                                <select name="tip_persona" class="form-select" required>
                                    <option value="">Seleccionar…</option>
                                    <option value="N">Natural</option>
                                    <option value="J">Jurídica</option>
                                </select>
                                <div class="invalid-feedback">Seleccione el tipo.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sexo <span class="text-danger">*</span></label>
                                <select name="sexo" class="form-select" required>
                                    <option value="">Seleccionar…</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="O">Otro</option>
                                    <option value="D">No Dice</option>
                                </select>
                                <div class="invalid-feedback">El sexo es obligatorio.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estado Civil <span class="text-danger">*</span></label>
                                <select name="est_civil" class="form-select" required>
                                    <option value="">Seleccionar…</option>
                                    <option value="S">Soltero/a</option>
                                    <option value="C">Casado/a</option>
                                    <option value="V">Viudo/a</option>
                                </select>
                                <div class="invalid-feedback">El estado civil es obligatorio.</div>
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

    {{-- ════════════════════════════════════════════════════════════
         MODAL: EDITAR PERSONA
    ════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-1"></i> Editar Persona
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" id="formEditar" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">DNI</label>
                                <input type="text" name="dni" id="edit_dni" class="form-control" maxlength="255">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Primer Nombre</label>
                                <input type="text" name="primer_nombre" id="edit_primer_nombre" class="form-control" maxlength="255">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" id="edit_segundo_nombre" class="form-control" maxlength="255">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Apellido</label>
                                <input type="text" name="apellido" id="edit_apellido" class="form-control" maxlength="255">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Edad</label>
                                <input type="number" name="edad" id="edit_edad" class="form-control" min="0" max="127">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tipo Persona</label>
                                <select name="tip_persona" id="edit_tip_persona" class="form-select">
                                    <option value="N">Natural</option>
                                    <option value="J">Jurídica</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Sexo</label>
                                <select name="sexo" id="edit_sexo" class="form-select">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="O">Otro</option>
                                    <option value="D">No Dice</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Estado Civil</label>
                                <select name="est_civil" id="edit_est_civil" class="form-select">
                                    <option value="S">Soltero/a</option>
                                    <option value="C">Casado/a</option>
                                    <option value="V">Viudo/a</option>
                                </select>
                            </div>

                            {{-- Sección teléfonos --}}
                            <div class="col-12"><hr><h6 class="text-muted">Teléfonos (opcional)</h6></div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Área Cel.</label>
                                <input type="number" name="num_area_cel" id="edit_num_area_cel" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Teléfono Cel.</label>
                                <input type="number" name="num_telefono_cel" id="edit_num_telefono_cel" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Área Ofi.</label>
                                <input type="number" name="num_area_ofi" id="edit_num_area_ofi" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Teléfono Ofi.</label>
                                <input type="number" name="num_telefono_ofi" id="edit_num_telefono_ofi" class="form-control">
                            </div>

                            {{-- Sección correo --}}
                            <div class="col-12"><hr><h6 class="text-muted">Correo (opcional)</h6></div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Usuario correo</label>
                                <input type="text" name="usuario_correo" id="edit_usuario_correo" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Servidor correo</label>
                                <input type="text" name="servidor_correo" id="edit_servidor_correo" class="form-control" placeholder="gmail.com">
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

    {{-- ════════════════════════════════════════════════════════════
         MODAL: CONFIRMAR BAJA
    ════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalBaja" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i> Confirmar baja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea dar de baja a <strong id="bajaLabel"></strong>?</p>
                    <p class="text-muted small">Esta acción desactivará el usuario asociado. No se eliminarán datos.</p>
                </div>
                <form method="POST" id="formBaja">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="accion" value="SOFT_DELETE">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-person-x me-1"></i> Dar de baja
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
// Esperar al evento show.bs.modal que Bootstrap sí dispara internamente
var modalEditar = document.getElementById('modalEditar');
if (modalEditar) {
    modalEditar.addEventListener('show.bs.modal', function (event) {
        var btn = event.relatedTarget;
        if (!btn) return;

        document.getElementById('edit_dni').value            = btn.getAttribute('data-dni')           ?? '';
        document.getElementById('edit_primer_nombre').value  = btn.getAttribute('data-primer_nombre') ?? '';
        document.getElementById('edit_segundo_nombre').value = btn.getAttribute('data-segundo_nombre')?? '';
        document.getElementById('edit_apellido').value       = btn.getAttribute('data-apellido')      ?? '';
        document.getElementById('edit_edad').value           = btn.getAttribute('data-edad')          ?? '';
        document.getElementById('edit_tip_persona').value    = btn.getAttribute('data-tip_persona')   ?? 'N';
        document.getElementById('edit_sexo').value           = btn.getAttribute('data-sexo')          ?? 'M';
        document.getElementById('edit_est_civil').value      = btn.getAttribute('data-est_civil')     ?? 'S';

        document.getElementById('formEditar').action = '/personas/' + btn.getAttribute('data-id');
    });
}

var modalBaja = document.getElementById('modalBaja');
if (modalBaja) {
    modalBaja.addEventListener('show.bs.modal', function (event) {
        var btn = event.relatedTarget;
        if (!btn) return;

        document.getElementById('bajaLabel').textContent = btn.getAttribute('data-nombre');
        document.getElementById('formBaja').action = '/personas/' + btn.getAttribute('data-id');
    });
}

// Validación formCrear
var formCrear = document.getElementById('formCrear');
if (formCrear) {
    formCrear.addEventListener('submit', function (e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
}
</script>
@endpush