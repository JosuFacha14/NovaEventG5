@extends('adminlte::page')

@section('title', 'Gestión de Ítems de Inventario')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">Gestión de Ítems de Inventario</h3>
        </div>
        <div class="col-sm-6 text-end">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Ítem
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

    {{-- Tabla de ítems --}}
    <x-adminlte-card title="Listado de Ítems" icon="bi bi-box-seam-fill" theme="primary" collapsible>
        <div class="table-responsive">
            <table id="tblItems" class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Almacén</th>
                        <th>Total</th>
                        <th>Disponible</th>
                        <th>Estado</th>
                        <th>Costo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td class="text-center">{{ $item['COD_ITEM'] }}</td>
                            <td>{{ $item['NOM_ITEM'] ?? '—' }}</td>
                            <td>{{ $item['NOM_CATEGORIA'] ?? '—' }}</td>
                            <td>{{ $item['NOM_ALMACEN'] ?? '—' }}</td>
                            <td class="text-center">{{ $item['CAN_TOTAL'] ?? 0 }}</td>
                            <td class="text-center">{{ $item['CAN_DISPONIBLE'] ?? 0 }}</td>
                            <td class="text-center">
                                @php $estado = $item['IND_ESTADO'] ?? 'ACTIVO'; @endphp
                                @if($estado === 'ACTIVO')
                                    <span class="badge bg-success">Activo</span>
                                @elseif($estado === 'MANTENIMIENTO')
                                    <span class="badge bg-warning text-dark">Mantenimiento</span>
                                @else
                                    <span class="badge bg-danger">Baja</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(!empty($item['MON_COSTO']))
                                    L. {{ number_format($item['MON_COSTO'], 2) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Botón editar --}}
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar{{ $item['COD_ITEM'] }}"
                                        title="Editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                {{-- Botón dar de baja --}}
                                @if(($item['IND_ESTADO'] ?? 'ACTIVO') !== 'BAJA')
                                    <button type="button" class="btn btn-danger btn-sm" title="Dar de baja"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalConfirmarBaja"
                                            data-action="{{ route('inventario.items.baja', $item['COD_ITEM']) }}"
                                            data-nombre="{{ $item['NOM_ITEM'] ?? '' }}">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal editar ítem --}}
                        <div class="modal fade" id="modalEditar{{ $item['COD_ITEM'] }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form method="POST"
                                      action="{{ route('inventario.items.update', $item['COD_ITEM']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Ítem</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" name="nom_item" class="form-control"
                                                           value="{{ $item['NOM_ITEM'] ?? '' }}" required maxlength="150"
                                                           pattern="[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+" title="Solo se permiten letras y espacios">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Código único</label>
                                                    <input type="text" name="cod_item_unico" class="form-control"
                                                           value="{{ $item['COD_ITEM_UNICO'] ?? '' }}" maxlength="50">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Descripción</label>
                                                <textarea name="des_item" class="form-control" rows="2">{{ $item['DES_ITEM'] ?? '' }}</textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Cantidad total <span class="text-danger">*</span></label>
                                                    <input type="number" name="can_total" class="form-control"
                                                           value="{{ $item['CAN_TOTAL'] ?? 0 }}" required min="0">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Cantidad disponible <span class="text-danger">*</span></label>
                                                    <input type="number" name="can_disponible" class="form-control"
                                                           value="{{ $item['CAN_DISPONIBLE'] ?? 0 }}" required min="0">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Costo (L.)</label>
                                                    <input type="number" name="mon_costo" class="form-control"
                                                           value="{{ $item['MON_COSTO'] ?? '' }}" min="0" step="0.01">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Fecha de adquisición</label>
                                                    <input type="date" name="fec_adquisicion" class="form-control"
                                                           value="{{ isset($item['FEC_ADQUISICION']) ? substr($item['FEC_ADQUISICION'], 0, 10) : '' }}">
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Categoría</label>
                                                    <select name="cod_categoria" class="form-select">
                                                        <option value="">Seleccione una categoría...</option>
                                                        @foreach($categorias as $cat)
                                                            <option value="{{ $cat['COD_CATEGORIA'] }}" {{ ($item['COD_CATEGORIA'] ?? '') == $cat['COD_CATEGORIA'] ? 'selected' : '' }}>#{{ $cat['COD_CATEGORIA'] }} — {{ $cat['NOM_CATEGORIA'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Almacén</label>
                                                    <select name="cod_almacen" class="form-select">
                                                        <option value="">Seleccione un almacén...</option>
                                                        @foreach($almacenes as $alm)
                                                            <option value="{{ $alm['COD_ALMACEN'] }}" {{ ($item['COD_ALMACEN'] ?? '') == $alm['COD_ALMACEN'] ? 'selected' : '' }}>#{{ $alm['COD_ALMACEN'] }} — {{ $alm['NOM_ALMACEN'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                No hay ítems registrados en el inventario.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- Modal crear ítem --}}
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('inventario.items.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-plus-circle me-1"></i> Nuevo Ítem de Inventario</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nom_item" class="form-control" required maxlength="150"
                                       placeholder="Ej: Micrófono inalámbrico"
                                       pattern="[A-Za-zÁÉÍÓÚáéíóúÑñüÜ\s]+" title="Solo se permiten letras y espacios">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Código único</label>
                                <input type="text" name="cod_item_unico" class="form-control" maxlength="50"
                                       placeholder="Ej: MIC-001">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="des_item" class="form-control" rows="2"
                                      placeholder="Descripción del ítem..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cantidad total <span class="text-danger">*</span></label>
                                <input type="number" name="can_total" class="form-control" required min="0" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Cantidad disponible <span class="text-danger">*</span></label>
                                <input type="number" name="can_disponible" class="form-control" required min="0" value="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Costo (L.)</label>
                                <input type="number" name="mon_costo" class="form-control" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="cod_categoria" class="form-select">
                                    <option value="">Seleccione una categoría...</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat['COD_CATEGORIA'] }}">#{{ $cat['COD_CATEGORIA'] }} — {{ $cat['NOM_CATEGORIA'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Almacén</label>
                                <select name="cod_almacen" class="form-select">
                                    <option value="">Seleccione un almacén...</option>
                                    @foreach($almacenes as $alm)
                                        <option value="{{ $alm['COD_ALMACEN'] }}">#{{ $alm['COD_ALMACEN'] }} — {{ $alm['NOM_ALMACEN'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de adquisición</label>
                                <input type="date" name="fec_adquisicion" class="form-control">
                            </div>

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
                    <p>¿Está seguro de que desea dar de baja el ítem <strong id="nombreItemBaja"></strong>?</p>
                    <p class="text-muted mb-0"><small>Esta acción cambiará el estado del ítem a BAJA. No se eliminarán los datos.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formBajaItem" method="POST" action="">
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
                document.getElementById('nombreItemBaja').textContent = button.getAttribute('data-nombre');
                document.getElementById('formBajaItem').action = button.getAttribute('data-action');
            });
        }

        // Inicializar DataTable
        if (typeof $.fn !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            $('#tblItems').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                pageLength: 10,
                order: [[0, 'asc']],
            });
        }
    });
</script>
@stop

