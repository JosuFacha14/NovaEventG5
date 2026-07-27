@extends('adminlte::page')

@section('title', 'Gestión de Almacenes')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Almacenes</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Almacén
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

    {{-- Tabla de almacenes --}}
    <x-adminlte-card title="Listado de Almacenes" icon="bi bi-building-fill" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblAlmacenes" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Dirección / Ubicación</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($almacenes as $alm)
                        <tr>
                            <td class="text-center">{{ $alm['COD_ALMACEN'] }}</td>
                            <td>{{ $alm['NOM_ALMACEN'] ?? '—' }}</td>
                            <td>{{ $alm['DIR_UBICACION'] ?? '—' }}</td>
                            <td class="text-center">{{ $alm['CAN_CAPACIDAD'] ?? '—' }}</td>
                            <td class="text-center">
                                @if($alm['IND_ACTIVO'] ?? 1)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Botón editar --}}
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar{{ $alm['COD_ALMACEN'] }}"
                                        title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                {{-- Botón dar de baja --}}
                                @if($alm['IND_ACTIVO'] ?? 1)
                                    <button type="button" class="btn btn-danger btn-sm" title="Dar de baja"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalConfirmarBaja"
                                            data-action="{{ route('inventario.almacenes.baja', $alm['COD_ALMACEN']) }}"
                                            data-nombre="{{ $alm['NOM_ALMACEN'] ?? '' }}">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal editar almacén --}}
                        <div class="modal fade" id="modalEditar{{ $alm['COD_ALMACEN'] }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST"
                                      action="{{ route('inventario.almacenes.update', $alm['COD_ALMACEN']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Almacén</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                                <input type="text" name="nom_almacen" class="form-control"
                                                       value="{{ $alm['NOM_ALMACEN'] ?? '' }}" required maxlength="100"
                                                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+" title="Solo se permiten letras y espacios">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Dirección / Ubicación</label>
                                                <input type="text" name="dir_ubicacion" class="form-control"
                                                       value="{{ $alm['DIR_UBICACION'] ?? '' }}" maxlength="200">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Capacidad</label>
                                                <input type="number" name="can_capacidad" class="form-control"
                                                       value="{{ $alm['CAN_CAPACIDAD'] ?? '' }}" min="0">
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
                                No hay almacenes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- Modal crear almacén --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('inventario.almacenes.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Nuevo Almacén</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nom_almacen" class="form-control" required maxlength="100"
                                   placeholder="Ej: Bodega Central"
                                   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+" title="Solo se permiten letras y espacios">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección / Ubicación</label>
                            <input type="text" name="dir_ubicacion" class="form-control" maxlength="200"
                                   placeholder="Ej: Col. Kennedy, Tegucigalpa">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Capacidad (unidades)</label>
                            <input type="number" name="can_capacidad" class="form-control" min="0"
                                   placeholder="Ej: 500">
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

    {{-- Modal confirmar baja --}}
    <div class="modal fade" id="modalConfirmarBaja" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-1"></i> Confirmar baja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea dar de baja el almacén <strong id="nombreAlmacenBaja"></strong>?</p>
                    <p class="text-muted mb-0"><small>Esta acción desactivará el almacén. No se eliminarán los datos.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formBajaAlmacen" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-person-dash-fill me-1"></i> Dar de baja
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Modal de confirmación de baja
        var modalBaja = document.getElementById('modalConfirmarBaja');
        if (modalBaja) {
            modalBaja.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('nombreAlmacenBaja').textContent = button.getAttribute('data-nombre');
                document.getElementById('formBajaAlmacen').action = button.getAttribute('data-action');
            });
        }

        // Inicializar DataTable
        if (typeof $.fn !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            $('#tblAlmacenes').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                pageLength: 10,
                order: [[0, 'asc']],
            });
        }
    });
</script>
@stop

