@extends('adminlte::page')

@section('title', 'Tipos de Cliente')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0"><i class="bi bi-person-badge me-2"></i>Catálogo — Tipos de Cliente</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearTipoCli">
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

    <x-adminlte-card title="Tipos de Cliente" icon="bi bi-person-badge" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblTiposCli" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tipos as $t)
                        <tr>
                            <td class="text-center fw-bold">{{ $t['COD_TIPO_CLI'] }}</td>
                            <td><span class="badge bg-info text-dark">{{ $t['NOM_TIPO_CLI'] }}</span></td>
                            <td>{{ $t['DES_TIPO_CLI'] }}</td>
                            <td class="text-center">
                                @if(($t['IND_TIPO_CLI'] ?? '1') == '1')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No hay tipos de cliente registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- ════════ MODAL: CREAR ════════ --}}
    <div class="modal fade" id="modalCrearTipoCli" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Nuevo Tipo de Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('tipos-cliente.store') }}" id="formCrearTipoCli" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nom_tipo_cli" class="form-control" maxlength="255" required
                                   pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                   placeholder="Ej. CORPORATIVO">
                            <div class="invalid-feedback">el nombre solo pueden ser letras</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                            <input type="text" name="des_tipo_cli" class="form-control" maxlength="255" required
                                   placeholder="Descripción breve…">
                            <div class="invalid-feedback">La descripción es obligatoria.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                            <select name="ind_tipo_cli" class="form-select" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
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

    // ── DataTable
    if (typeof DataTable !== 'undefined') {
        new DataTable('#tblTiposCli', {
            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            order: [[0, 'desc']],
        });
    }

    // ── Validación Crear
    const formCrear = document.getElementById('formCrearTipoCli');
    formCrear.addEventListener('submit', function (e) {
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });

    });

});
</script>
@endpush