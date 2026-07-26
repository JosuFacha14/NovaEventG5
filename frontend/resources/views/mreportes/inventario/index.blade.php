@extends('adminlte::page')

@section('title', 'Inventario Utilizado')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Reporte de Inventario</h1>
        <button type="button" class="btn btn-primary"
            data-toggle="modal" data-target="#modalFormInventario"
            data-bs-toggle="modal" data-bs-target="#modalFormInventario"
            onclick="abrirModalCrear()">
            <i class="fas fa-plus-circle mr-1"></i> + Registrar Inventario
        </button>
    </div>
@endsection

@section('content')
    {{-- Tarjeta resumen --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-boxes fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total de Registros de Inventario</div>
                        <div class="h5 mb-0 fw-bold">{{ count($inventario ?? []) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Items Utilizados por Evento</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th># Cód.</th>
                            <th>Evento</th>
                            <th>Item</th>
                            <th>Cant. Utilizada</th>
                            <th>Estado Final</th>
                            <th>Notas</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventario as $inv)
                            @php
                                $codRep    = $inv->COD_REP_INVENTARIO ?? $inv->cod_rep_inventario;
                                $codEvento = $inv->COD_EVENTO ?? $inv->cod_evento;
                                $codItem   = $inv->COD_ITEM ?? $inv->cod_item;
                                $cant      = $inv->CAN_UTILIZADA ?? $inv->can_utilizada ?? 0;
                                $estado    = $inv->DES_ESTADO_FINAL ?? $inv->des_estado_final ?? '';
                                $notas     = $inv->OBS_NOTAS ?? $inv->obs_notas ?? '';
                            @endphp
                            <tr>
                                <td>{{ $codRep }}</td>
                                <td>Evento #{{ $codEvento }}</td>
                                <td>Item #{{ $codItem }}</td>
                                <td>{{ $cant }}</td>
                                <td>{{ $estado ?: '-' }}</td>
                                <td>{{ $notas ?: '-' }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-xs" title="Editar"
                                        data-toggle="modal" data-target="#modalFormInventario"
                                        data-bs-toggle="modal" data-bs-target="#modalFormInventario"
                                        onclick="abrirModalEditar('{{ $codRep }}','{{ $codItem }}','{{ $codEvento }}','{{ $cant }}','{{ addslashes($estado) }}','{{ addslashes($notas) }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-3 text-muted">No hay registros de inventario.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalFormInventario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="position:relative;">
                    <h5 class="modal-title" id="modalTitleInventario">
                        <i class="fas fa-plus-circle text-primary mr-1"></i> Registrar Uso de Inventario
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="position:absolute;right:15px;top:10px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formInventario">
                    @csrf
                    <input type="hidden" id="inventario_id" name="id">
                    <div class="modal-body">
                        <div id="modal-error-container"></div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Evento <span class="text-danger">*</span></label>
                                <select id="cod_evento" name="cod_evento" class="form-control" required>
                                    <option value="">Seleccione un evento...</option>
                                    @foreach($eventos as $ev)
                                        <option value="{{ $ev->COD_EVENTO }}">
                                            #{{ $ev->COD_EVENTO }} &ndash; {{ $ev->NOM_EVENTO }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Item <span class="text-danger">*</span></label>
                                <select id="cod_item" name="cod_item" class="form-control" required>
                                    <option value="">Seleccione un item...</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->COD_ITEM ?? $item->cod_item }}">
                                            #{{ $item->COD_ITEM ?? $item->cod_item }} &ndash; {{ $item->NOM_ITEM ?? $item->nom_item ?? 'Sin nombre' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Cantidad Utilizada <span class="text-danger">*</span></label>
                                <input type="number" id="can_utilizada" class="form-control" required min="1" placeholder="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Estado Final</label>
                                <input type="text" id="des_estado_final" class="form-control" placeholder="Ej. Intacto, Dañado...">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Notas Adicionales</label>
                            <textarea id="obs_notas" class="form-control" rows="3" placeholder="Comentarios u observaciones..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function abrirModalCrear() {
        document.getElementById('modalTitleInventario').innerHTML = '<i class="fas fa-plus-circle text-primary mr-1"></i> Registrar Uso de Inventario';
        document.getElementById('formInventario').reset();
        document.getElementById('inventario_id').value = '';
        document.getElementById('modal-error-container').innerHTML = '';
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#modalFormInventario').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('modalFormInventario')).show();
        }
    }

    function abrirModalEditar(id, item, evt, cant, estado, notas) {
        document.getElementById('modalTitleInventario').innerHTML = '<i class="fas fa-edit text-warning mr-1"></i> Editar Uso de Inventario';
        document.getElementById('inventario_id').value = id;
        document.getElementById('cod_item').value = item;
        document.getElementById('cod_evento').value = evt;
        document.getElementById('can_utilizada').value = cant;
        document.getElementById('des_estado_final').value = estado !== 'null' ? estado : '';
        document.getElementById('obs_notas').value = notas !== 'null' ? notas : '';
        document.getElementById('modal-error-container').innerHTML = '';
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#modalFormInventario').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('modalFormInventario')).show();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('formInventario').addEventListener('submit', function (e) {
            e.preventDefault();

            const id = document.getElementById('inventario_id').value;
            const url = id
                ? "{{ url('mreportes/inventario/actualizar') }}/" + id
                : "{{ route('inventario.store') }}";
            const method = id ? 'PUT' : 'POST';

            const data = {
                _token:           document.querySelector('input[name=_token]').value,
                cod_item:         document.getElementById('cod_item').value,
                cod_evento:       document.getElementById('cod_evento').value,
                can_utilizada:    document.getElementById('can_utilizada').value,
                des_estado_final: document.getElementById('des_estado_final').value || null,
                obs_notas:        document.getElementById('obs_notas').value || null
            };

            fetch(url, {
                method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._token },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) { location.reload(); }
                else {
                    document.getElementById('modal-error-container').innerHTML =
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${res.message}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                         </div>`;
                }
            })
            .catch(() => {
                document.getElementById('modal-error-container').innerHTML =
                    `<div class="alert alert-danger">Ocurrió un error al procesar la solicitud.</div>`;
            });
        });
    });
</script>
@endsection