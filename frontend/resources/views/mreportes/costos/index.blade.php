@extends('adminlte::page')

@section('title', 'Costos Operativos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Reporte de Costos Operativos</h1>
        <button type="button" class="btn btn-primary"
            data-toggle="modal" data-target="#modalFormCosto"
            data-bs-toggle="modal" data-bs-target="#modalFormCosto"
            onclick="abrirModalCrear()">
            <i class="fas fa-plus-circle mr-1"></i> + Registrar Costo
        </button>
    </div>
@endsection

@section('content')
    {{-- Tarjetas resumen --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-calculator fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Presupuestado</div>
                        <div class="h5 mb-0 fw-bold">L. {{ number_format($totalPresupuestado ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-receipt fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Real Ejecutado</div>
                        <div class="h5 mb-0 fw-bold">L. {{ number_format($totalReal ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-danger shadow-sm">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Detalle de Costos por Evento</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cód.</th>
                            <th>Evento</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Presupuestado</th>
                            <th>Real</th>
                            <th>Desviación</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costos as $c)
                            @php
                                $pres      = $c->MON_PRESUPUESTADO ?? $c->mon_presupuestado ?? 0;
                                $real      = $c->MON_REAL ?? $c->mon_real ?? 0;
                                $desviacion = $real - $pres;
                                $codCosto  = $c->COD_COSTO ?? $c->cod_costo;
                                $codEvento = $c->COD_EVENTO ?? $c->cod_evento;
                                $codRep    = $c->COD_REPORTE ?? $c->cod_reporte ?? '';
                                $codProv   = $c->COD_PROVEEDOR ?? $c->cod_proveedor ?? '';
                                $cat       = $c->IND_CATEGORIA ?? $c->ind_categoria;
                                $desc      = $c->DES_COSTO ?? $c->des_costo ?? '';
                                $obs       = $c->OBS_COSTO ?? $c->obs_costo ?? '';
                            @endphp
                            <tr>
                                <td>{{ $codCosto }}</td>
                                <td>Evento #{{ $codEvento }}</td>
                                <td>{{ $cat }}</td>
                                <td>{{ $desc }}</td>
                                <td>L. {{ number_format($pres, 2) }}</td>
                                <td>L. {{ number_format($real, 2) }}</td>
                                <td class="{{ $desviacion <= 0 ? 'text-success' : 'text-danger' }}">
                                    L. {{ number_format($desviacion, 2) }}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-xs" title="Editar"
                                        data-toggle="modal" data-target="#modalFormCosto"
                                        data-bs-toggle="modal" data-bs-target="#modalFormCosto"
                                        onclick="abrirModalEditar('{{ $codCosto }}','{{ $codEvento }}','{{ $codRep }}','{{ $codProv }}','{{ $cat }}','{{ addslashes($desc) }}','{{ $pres }}','{{ $real }}','{{ addslashes($obs) }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-3 text-muted">No hay registros de costos operativos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Formulario --}}
    <div class="modal fade" id="modalFormCosto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="position:relative;">
                    <h5 class="modal-title" id="modalTitleCosto">
                        <i class="fas fa-plus-circle text-primary mr-1"></i> Registrar Nuevo Costo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="position:absolute;right:15px;top:10px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCosto">
                    @csrf
                    <input type="hidden" id="costo_id" name="id">
                    <div class="modal-body">
                        <div id="modal-error-container"></div>


                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
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
                            <div class="col-md-4 form-group mb-3">
                                <label>Reporte (Opcional)</label>
                                <select id="cod_reporte" name="cod_reporte" class="form-control">
                                    <option value="">Ninguno</option>
                                    @foreach($reportes as $rep)
                                        <option value="{{ $rep->COD_REPORTE ?? $rep->cod_reporte }}">
                                            #{{ $rep->COD_REPORTE ?? $rep->cod_reporte }} – {{ $rep->TIP_REPORTE ?? $rep->tip_reporte }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>Proveedor (Opcional)</label>
                                <select id="cod_proveedor" name="cod_proveedor" class="form-control">
                                    <option value="">Ninguno</option>
                                    @foreach($proveedores as $prov)
                                        <option value="{{ $prov->COD_PROVEEDOR }}">
                                            #{{ $prov->COD_PROVEEDOR }} &ndash; {{ $prov->EMPRESA }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Categoría <span class="text-danger">*</span></label>
                                <select id="ind_categoria" name="ind_categoria" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="PERSONAL">PERSONAL</option>
                                    <option value="LOGISTICA">LOGISTICA</option>
                                    <option value="MARKETING">MARKETING</option>
                                    <option value="EQUIPAMIENTO">EQUIPAMIENTO</option>
                                    <option value="SERVICIOS">SERVICIOS</option>
                                    <option value="OTROS">OTROS</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Descripción del Costo</label>
                                <input type="text" id="des_costo" name="des_costo" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Monto Presupuestado (L.) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" id="mon_presupuestado" name="mon_presupuestado" class="form-control" required placeholder="0.00">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>Monto Real (L.) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46" id="mon_real" name="mon_real" class="form-control" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Observaciones</label>
                            <textarea id="obs_costo" name="obs_costo" class="form-control" rows="2"></textarea>
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
        document.getElementById('modalTitleCosto').innerHTML = '<i class="fas fa-plus-circle text-primary mr-1"></i> Registrar Nuevo Costo';
        document.getElementById('formCosto').reset();
        document.getElementById('costo_id').value = '';
        document.getElementById('modal-error-container').innerHTML = '';
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#modalFormCosto').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('modalFormCosto')).show();
        }
    }

    function abrirModalEditar(id, evt, rep, prov, cat, desc, pres, real, obs) {
        document.getElementById('modalTitleCosto').innerHTML = '<i class="fas fa-edit text-warning mr-1"></i> Editar Costo Operativo';
        document.getElementById('costo_id').value = id;
        document.getElementById('cod_evento').value = evt;
        document.getElementById('cod_reporte').value = (rep && rep !== 'null') ? rep : '';
        document.getElementById('cod_proveedor').value = (prov && prov !== 'null') ? prov : '';
        document.getElementById('ind_categoria').value = cat;
        document.getElementById('des_costo').value = desc !== 'null' ? desc : '';
        document.getElementById('mon_presupuestado').value = pres;
        document.getElementById('mon_real').value = real;
        document.getElementById('obs_costo').value = obs !== 'null' ? obs : '';
        document.getElementById('modal-error-container').innerHTML = '';
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#modalFormCosto').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('modalFormCosto')).show();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('formCosto').addEventListener('submit', function (e) {
            e.preventDefault();

            const id = document.getElementById('costo_id').value;
            const url = id
                ? "{{ url('mreportes/costos/actualizar') }}/" + id
                : "{{ route('costos.store') }}";
            const method = id ? 'PUT' : 'POST';

            const data = {
                _token: document.querySelector('input[name=_token]').value,
                cod_evento: document.getElementById('cod_evento').value,
                cod_reporte: document.getElementById('cod_reporte').value || null,
                cod_proveedor: document.getElementById('cod_proveedor').value || null,
                ind_categoria: document.getElementById('ind_categoria').value,
                des_costo: document.getElementById('des_costo').value,
                mon_presupuestado: document.getElementById('mon_presupuestado').value,
                mon_real: document.getElementById('mon_real').value,
                obs_costo: document.getElementById('obs_costo').value
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