@extends('adminlte::page')

@section('title', 'Panel de Reportes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Panel General de Reportes</h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalFormReporte" data-bs-toggle="modal" data-bs-target="#modalFormReporte" onclick="abrirModalCrear()">
            <i class="fas fa-plus-circle mr-1"></i> + Nuevo Reporte
        </button>
    </div>
@endsection

@section('content')
    {{-- Tarjetas resumen --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-calendar-alt fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Reportes Registrados</div>
                        <div class="h5 mb-0 fw-bold">{{ collect($datos['reportes_lista'] ?? [])->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-dollar-sign fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Utilidad Total</div>
                        <div class="h5 mb-0 fw-bold">L. {{ number_format($datos['total_ganancias'] ?? 0, 2) }}</div>
                    </div>
                </div>
                <a href="{{ route('reportes.ganancias') }}" class="card-footer text-decoration-none small text-muted">
                    Ver Ganancias <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center" style="width:54px;height:54px;flex-shrink:0">
                        <i class="fas fa-file-invoice-dollar fa-lg text-white"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Costos Operativos</div>
                        <div class="h5 mb-0 fw-bold">L. {{ number_format($datos['total_costos'] ?? 0, 2) }}</div>
                    </div>
                </div>
                <a href="{{ route('reportes.costos') }}" class="card-footer text-decoration-none small text-muted">
                    Ver Costos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla con Datos y Botones  -->
    <div class="card card-dark card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Listado de Reportes Generados</h3>
        </div>
        <div class="card-body p-0">
            <div id="error-container"></div>
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Tipo Reporte</th>
                            <th>Fec. Emisión</th>
                            <th>Período Desde</th>
                            <th>Período Hasta</th>
                            <th>Observaciones</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datos['reportes_lista'] ?? [] as $reporte)
                            <tr>
                                <td>{{ $reporte->COD_REPORTE ?? $reporte->cod_reporte }}</td>
                                <td>{{ $reporte->TIP_REPORTE ?? $reporte->tip_reporte }}</td>
                                <td>{{ \Carbon\Carbon::parse($reporte->FEC_EMISION ?? $reporte->fec_emision)->format('d/m/Y H:i') }}</td>
                                <td>{{ $reporte->FEC_PERIODO_DESDE ?? $reporte->fec_periodo_desde ? \Carbon\Carbon::parse($reporte->FEC_PERIODO_DESDE ?? $reporte->fec_periodo_desde)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $reporte->FEC_PERIODO_HASTA ?? $reporte->fec_periodo_hasta ? \Carbon\Carbon::parse($reporte->FEC_PERIODO_HASTA ?? $reporte->fec_periodo_hasta)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $reporte->OBS_REPORTE ?? $reporte->obs_reporte ?? '-' }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-xs" title="Editar"
                                        data-toggle="modal" data-target="#modalFormReporte"
                                        data-bs-toggle="modal" data-bs-target="#modalFormReporte"
                                        onclick="abrirModalEditar(
                                        '{{ $reporte->COD_REPORTE ?? $reporte->cod_reporte }}', 
                                        '{{ $reporte->TIP_REPORTE ?? $reporte->tip_reporte }}',
                                        '{{ $reporte->FEC_PERIODO_DESDE ?? $reporte->fec_periodo_desde ? \Carbon\Carbon::parse($reporte->FEC_PERIODO_DESDE ?? $reporte->fec_periodo_desde)->format('Y-m-d') : '' }}',
                                        '{{ $reporte->FEC_PERIODO_HASTA ?? $reporte->fec_periodo_hasta ? \Carbon\Carbon::parse($reporte->FEC_PERIODO_HASTA ?? $reporte->fec_periodo_hasta)->format('Y-m-d') : '' }}',
                                        '{{ $reporte->OBS_REPORTE ?? $reporte->obs_reporte }}'
                                    )">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">
                                    No hay reportes registrados aún. Usa el botón <b>"+ Nuevo Reporte"</b> para agregar uno.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar Reporte --}}
    <div class="modal fade" id="modalFormReporte" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="position:relative;">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-plus-circle text-primary mr-1"></i> Registrar Nuevo Reporte
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:absolute;right:15px;top:10px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formReporte">
                    @csrf
                    <input type="hidden" id="reporte_id" name="id">
                    <div class="modal-body">
                        <div id="modal-error-container"></div>
                        <div class="form-group mb-3">
                            <label class="text-muted small"><i class="fas fa-hashtag mr-1"></i> Código de Reporte</label>
                            <input type="text" class="form-control form-control-sm" value="Se asigna automáticamente" disabled style="font-style:italic; color:#888;">
                        </div>
                        <div class="form-group">
                            <label>Tipo de Reporte <span class="text-danger">*</span></label>
                            <select class="form-control" id="tip_reporte" required>
                                <option value="">Seleccione...</option>
                                <option value="VENTAS">VENTAS</option>
                                <option value="INVENTARIO">INVENTARIO</option>
                                <option value="FINANCIERO">FINANCIERO</option>
                                <option value="GENERAL">GENERAL</option>
                            </select>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 form-group">
                                <label>Período Desde</label>
                                <input type="date" class="form-control" id="fec_periodo_desde">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Período Hasta</label>
                                <input type="date" class="form-control" id="fec_periodo_hasta">
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label>Observaciones</label>
                            <textarea class="form-control" id="obs_reporte" rows="3" placeholder="Observaciones del reporte..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle text-primary mr-1"></i> Registrar Nuevo Reporte';
        document.getElementById('formReporte').reset();
        document.getElementById('reporte_id').value = '';
        document.getElementById('modal-error-container').innerHTML = '';
        
        if (typeof $ !== 'undefined' && $('#modalFormReporte').modal) {
            $('#modalFormReporte').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            var myModal = new bootstrap.Modal(document.getElementById('modalFormReporte'));
            myModal.show();
        }
    }

    function abrirModalEditar(id, tipo, desde, hasta, obs) {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit text-primary mr-1"></i> Editar Reporte';
        document.getElementById('reporte_id').value = id;
        document.getElementById('tip_reporte').value = tipo;
        document.getElementById('fec_periodo_desde').value = desde;
        document.getElementById('fec_periodo_hasta').value = hasta;
        document.getElementById('obs_reporte').value = obs !== 'null' ? obs : '';
        document.getElementById('modal-error-container').innerHTML = '';
        
        if (typeof $ !== 'undefined' && $('#modalFormReporte').modal) {
            $('#modalFormReporte').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            var myModal = new bootstrap.Modal(document.getElementById('modalFormReporte'));
            myModal.show();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        $('#formReporte').submit(function(e) {
            e.preventDefault();
            
            let id = $('#reporte_id').val();
            let url = id ? "{{ url('mreportes/reportes/actualizar') }}/" + id : "{{ route('reportes.store') }}";
            let method = id ? "PUT" : "POST";
            
            let data = {
                _token: $('input[name=_token]').val(),
                tip_reporte: $('#tip_reporte').val(),
                fec_periodo_desde: $('#fec_periodo_desde').val() || null,
                fec_periodo_hasta: $('#fec_periodo_hasta').val() || null,
                obs_reporte: $('#obs_reporte').val()
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    $('#modal-error-container').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${data.message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#modal-error-container').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Ocurrió un error al procesar la solicitud.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
            });
        });
    });
</script>
@endsection