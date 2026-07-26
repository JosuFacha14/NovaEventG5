@extends('adminlte::page')

@section('title', 'Ganancias')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Ganancias y Utilidades</h1>
        <button type="button" class="btn btn-success"
            data-toggle="modal" data-target="#modalFormGanancia"
            data-bs-toggle="modal" data-bs-target="#modalFormGanancia"
            onclick="abrirModalCrear()">
            <i class="fas fa-plus-circle mr-1"></i> + Registrar Ganancia
        </button>
    </div>
@endsection

@section('content')
    {{-- Tarjetas resumen --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-arrow-up fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Ingresos</div>
                        <div class="h5 mb-0 fw-bold">L. {{ number_format($totalIngresos ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-arrow-down fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Costos</div>
                        <div class="h5 mb-0 fw-bold">L. {{ number_format($totalCostos ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-wallet fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Utilidad Neta</div>
                        <div class="h5 mb-0 fw-bold">L. {{ number_format($totalUtilidad ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Reporte de Ganancias por Evento</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th># Cód.</th>
                            <th>Evento</th>
                            <th>Ingresos</th>
                            <th>Costos</th>
                            <th>Utilidad</th>
                            <th>Fecha Cierre</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ganancias as $g)
                            @php
                                $ingresos    = $g->MON_INGRESOS ?? $g->mon_ingresos ?? 0;
                                $costos      = $g->MON_COSTOS ?? $g->mon_costos ?? 0;
                                $utilidad    = $g->MON_UTILIDAD ?? $g->mon_utilidad ?? 0;
                                $codGanancia = $g->COD_GANANCIA ?? $g->cod_ganancia;
                                $codEvento   = $g->COD_EVENTO ?? $g->cod_evento;
                                $fecCierre   = $g->FEC_CIERRE ?? $g->fec_cierre;
                            @endphp
                            <tr>
                                <td>{{ $codGanancia }}</td>
                                <td>Evento #{{ $codEvento }}</td>
                                <td>L. {{ number_format($ingresos, 2) }}</td>
                                <td>L. {{ number_format($costos, 2) }}</td>
                                <td class="{{ $utilidad >= 0 ? 'text-success' : 'text-danger' }}">
                                    L. {{ number_format($utilidad, 2) }}
                                </td>
                                <td>{{ $fecCierre ? \Carbon\Carbon::parse($fecCierre)->format('d/m/Y') : '-' }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-xs" title="Editar"
                                        data-toggle="modal" data-target="#modalFormGanancia"
                                        data-bs-toggle="modal" data-bs-target="#modalFormGanancia"
                                        onclick="abrirModalEditar('{{ $codGanancia }}','{{ $codEvento }}','{{ $ingresos }}','{{ $costos }}','{{ $utilidad }}','{{ $fecCierre ? \Carbon\Carbon::parse($fecCierre)->format('Y-m-d') : '' }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-3 text-muted">No hay registros de ganancias.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modalFormGanancia" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="position:relative;">
                    <h5 class="modal-title" id="modalTitleGanancia">
                        <i class="fas fa-plus-circle text-success mr-1"></i> Registrar Ganancia
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="position:absolute;right:15px;top:10px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formGanancia">
                    @csrf
                    <input type="hidden" id="ganancia_id" name="id">
                    <div class="modal-body">
                        <div id="modal-error-container"></div>

                        

                        <div class="form-group mb-3">
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

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Ingresos Totales (L.) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" id="mon_ingresos" class="form-control" required
                                    placeholder="0.00" oninput="calcularUtilidad()">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Costos Totales (L.) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" id="mon_costos" class="form-control" required
                                    placeholder="0.00" oninput="calcularUtilidad()">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Utilidad Neta (L.) </label>
                                <input type="number" step="0.01" id="mon_utilidad" class="form-control"
                                    readonly tabindex="-1">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Fecha de Cierre</label>
                                <input type="date" id="fec_cierre" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function calcularUtilidad() {
        const ing  = parseFloat(document.getElementById('mon_ingresos').value) || 0;
        const cost = parseFloat(document.getElementById('mon_costos').value)   || 0;
        document.getElementById('mon_utilidad').value = (ing - cost).toFixed(2);
    }

    function abrirModalCrear() {
        document.getElementById('modalTitleGanancia').innerHTML = '<i class="fas fa-plus-circle text-success mr-1"></i> Registrar Ganancia';
        document.getElementById('formGanancia').reset();
        document.getElementById('ganancia_id').value = '';
        document.getElementById('mon_utilidad').value = '';
        document.getElementById('modal-error-container').innerHTML = '';
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#modalFormGanancia').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('modalFormGanancia')).show();
        }
    }

    function abrirModalEditar(id, evt, ing, cost, util, fec) {
        document.getElementById('modalTitleGanancia').innerHTML = '<i class="fas fa-edit text-warning mr-1"></i> Editar Ganancia';
        document.getElementById('ganancia_id').value = id;
        document.getElementById('cod_evento').value = evt;
        document.getElementById('mon_ingresos').value = ing;
        document.getElementById('mon_costos').value = cost;
        document.getElementById('mon_utilidad').value = util;
        document.getElementById('fec_cierre').value = fec;
        document.getElementById('modal-error-container').innerHTML = '';
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#modalFormGanancia').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('modalFormGanancia')).show();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('formGanancia').addEventListener('submit', function (e) {
            e.preventDefault();
            calcularUtilidad();

            const id = document.getElementById('ganancia_id').value;
            const url = id
                ? "{{ url('mreportes/ganancias/actualizar') }}/" + id
                : "{{ route('ganancias.store') }}";
            const method = id ? 'PUT' : 'POST';

            const data = {
                _token: document.querySelector('input[name=_token]').value,
                cod_evento:   document.getElementById('cod_evento').value,
                mon_ingresos: document.getElementById('mon_ingresos').value,
                mon_costos:   document.getElementById('mon_costos').value,
                mon_utilidad: document.getElementById('mon_utilidad').value,
                fec_cierre:   document.getElementById('fec_cierre').value || null
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