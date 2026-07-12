@extends('adminlte::page')

@section('title', 'Tipos de Usuario')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Catálogo — Tipos de Usuario</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearTipoUsr">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo
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

    <x-adminlte-card title="Tipos de Usuario" icon="bi bi-shield-lock" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblTiposUsr" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tipos as $t)
                        <tr>
                            <td class="text-center">{{ $t['COD_TIPO_USR'] }}</td>
                            <td><span class="badge bg-primary">{{ $t['NOM_TIPO'] }}</span></td>
                            <td>{{ $t['DES_TIPO'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay tipos de usuario registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- ════════ MODAL: CREAR TIPO USUARIO ════════ --}}
    <div class="modal fade" id="modalCrearTipoUsr" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('tipos-usuario.store') }}" id="formCrearTipoUsr" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nom_tipo" class="form-control" maxlength="255" required
                                   placeholder="Ej. ADMINISTRADOR">
                            <div class="invalid-feedback">El nombre es obligatorio.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                            <textarea name="des_tipo" class="form-control" rows="3" maxlength="2000" required
                                      placeholder="Describe el rol y sus permisos…"></textarea>
                            <div class="invalid-feedback">La descripción es obligatoria.</div>
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

@stop

@section('plugins.Datatables', true)

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof DataTable !== 'undefined') {
        new DataTable('#tblTiposUsr', {
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            order: [[0, 'desc']],
        });
    }

    const form = document.getElementById('formCrearTipoUsr');
    form.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

});
</script>
@endpush