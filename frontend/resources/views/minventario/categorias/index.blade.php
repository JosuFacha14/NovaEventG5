@extends('adminlte::page')

@section('title', 'Catálogo de Categorías')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Catálogo de Categorías de Inventario</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
            </button>
        </div>
    </div>
@stop

@section('content')

    {{-- Alertas de sesión --}}
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

    {{-- Tabla de categorías --}}
    <x-adminlte-card title="Listado de Categorías" icon="bi bi-tag-fill" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblCategorias" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Ícono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $cat)
                        <tr>
                            <td class="text-center">{{ $cat['COD_CATEGORIA'] }}</td>
                            <td>{{ $cat['NOM_CATEGORIA'] ?? '—' }}</td>
                            <td>{{ $cat['DES_CATEGORIA'] ?? '—' }}</td>
                            <td class="text-center">
                                @if(!empty($cat['DES_ICONO']))
                                    <i class="{{ $cat['DES_ICONO'] }}"></i>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($cat['IND_ACTIVA'] ?? 1))
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Botón editar --}}
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar{{ $cat['COD_CATEGORIA'] }}"
                                        title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                {{-- Botón dar de baja --}}
                                @if($cat['IND_ACTIVA'] ?? 1)
                                    <form method="POST"
                                          action="{{ route('inventario.categorias.baja', $cat['COD_CATEGORIA']) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('¿Desactivar esta categoría?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Dar de baja">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal editar categoría --}}
                        <div class="modal fade" id="modalEditar{{ $cat['COD_CATEGORIA'] }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST"
                                      action="{{ route('inventario.categorias.update', $cat['COD_CATEGORIA']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Categoría</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                                <input type="text" name="nom_categoria" class="form-control"
                                                       value="{{ $cat['NOM_CATEGORIA'] ?? '' }}" required maxlength="100">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Descripción</label>
                                                <textarea name="des_categoria" class="form-control" rows="3">{{ $cat['DES_CATEGORIA'] ?? '' }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Clase de ícono (Bootstrap Icons)</label>
                                                <input type="text" name="des_icono" class="form-control"
                                                       value="{{ $cat['DES_ICONO'] ?? '' }}" maxlength="50"
                                                       placeholder="bi bi-tag-fill">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-save me-1"></i> Guardar cambios
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                No hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- Modal crear categoría --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('inventario.categorias.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Nueva Categoría</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nom_categoria" class="form-control" required maxlength="100"
                                   placeholder="Ej: Mobiliario">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="des_categoria" class="form-control" rows="3"
                                      placeholder="Descripción de la categoría..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Clase de ícono (Bootstrap Icons)</label>
                            <input type="text" name="des_icono" class="form-control" maxlength="50"
                                   placeholder="Ej: bi bi-tag-fill">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop


@section('js')
<script>
    // Inicializar DataTable para búsqueda y paginación
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#tblCategorias').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                pageLength: 10,
                order: [[0, 'asc']],
            });
        }
    });
</script>
@stop
