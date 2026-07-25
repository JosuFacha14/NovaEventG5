@extends('adminlte::page')

@section('title', 'Ganancias y Utilidades')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Reporte de Ganancias y Utilidades</h1>
        <button type="button" class="btn btn-success" onclick="abrirModalGanancia()">
            <i class="fas fa-plus-circle mr-1"></i> + Registrar Ganancia
        </button>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="info-box bg-light elevation-1">
                <span class="info-box-icon bg-primary"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Ingresos</span>
                    <span class="info-box-number text-lg">L. {{ number_format($totalIngresos ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-light elevation-1">
                <span class="info-box-icon bg-warning"><i class="fas fa-funnel-dollar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Costos</span>
                    <span class="info-box-number text-lg">L. {{ number_format($totalCostos ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-light elevation-1">
                <span class="info-box-icon bg-success"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Utilidad Neta</span>
                    <span class="info-box-number text-lg">L. {{ number_format($totalUtilidad ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-coins mr-1"></i> Detalle de Ganancias por Evento</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cód. Ganancia</th>
                            <th>Cód. Evento</th>
                            <th>Monto Ingresos</th>
                            <th>Monto Costos</th>
                            <th>Utilidad Neta</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ganancias as $g)
                            @php
                                $ing = $g->MON_INGRESOS ?? $g->mon_ingresos ?? 0;
                                $cos = $g->MON_COSTOS ?? $g->mon_costos ?? 0;
                                $uti = $g->MON_UTILIDAD ?? $g->mon_utilidad ?? ($ing - $cos);
                                $codG = $g->COD_GANANCIA ?? $g->cod_ganancia ?? '-';
                                $codE = $g->COD_EVENTO ?? $g->cod_evento ?? '1';
                            @endphp
                            <tr>
                                <td>{{ $codG }}</td>
                                <td><span class="badge badge-info">Evento #{{ $codE }}</span></td>
                                <td>L. {{ number_format($ing, 2) }}</td>
                                <td>L. {{ number_format($cos, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $uti >= 0 ? 'success' : 'danger' }}">
                                        L. {{ number_format($uti, 2) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-sm mr-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarBajaGanancia('{{ $codG }}')" title="Dar de baja">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-3 text-muted">No hay registros de ganancias.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Ganancia -->
    <div class="modal fade" id="modalCrearGanancia" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="fas fa-plus-circle text-success"></i> Registrar Ganancia</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <form id="formCrearGanancia">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Código Evento</label>
                            <input type="number" id="cod_evento" name="cod_evento" class="form-control bg-secondary text-white" value="1" required placeholder="Ej. 1">
                        </div>
                        <div class="form-group mb-3">
                            <label>Monto Ingresos (L.)</label>
                            <input type="number" step="0.01" id="mon_ingresos" name="mon_ingresos" class="form-control bg-secondary text-white" required placeholder="0.00">
                        </div>
                        <div class="form-group mb-3">
                            <label>Monto Costos (L.)</label>
                            <input type="number" step="0.01" id="mon_costos" name="mon_costos" class="form-control bg-secondary text-white" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnGuardarGanancia" onclick="ejecutarGuardadoGanancia()" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Baja -->
    <div class="modal fade" id="modalBajaGanancia" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmar baja</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="txtBajaGanancia">¿Está seguro de que desea dar de baja este registro de ganancia?</p>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger"><i class="fas fa-user-minus"></i> Dar de baja</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    var guardandoGananciaProceso = false;

    function abrirModalGanancia() {
        if (typeof $ !== 'undefined' && $('#modalCrearGanancia').modal) {
            $('#modalCrearGanancia').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            var myModal = new bootstrap.Modal(document.getElementById('modalCrearGanancia'));
            myModal.show();
        } else {
            var el = document.getElementById('modalCrearGanancia');
            el.classList.add('show');
            el.style.display = 'block';
        }
    }

    function ejecutarGuardadoGanancia() {
        if (guardandoGananciaProceso) return;

        const btn = document.getElementById('btnGuardarGanancia');
        const form = document.getElementById('formCrearGanancia');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        guardandoGananciaProceso = true;
        if (btn) btn.disabled = true;

        const formData = new FormData(form);

        fetch("{{ route('ganancias.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.ok) {
                location.reload();
            } else {
                alert('Error al guardar: ' + (data.message || data.msg || 'Verifique los datos'));
                guardandoGananciaProceso = false;
                if (btn) btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error en la solicitud.');
            guardandoGananciaProceso = false;
            if (btn) btn.disabled = false;
        });
    }

    function confirmarBajaGanancia(id) {
        document.getElementById('txtBajaGanancia').innerText = `¿Está seguro de que desea dar de baja la Ganancia #${id}?`;
        if (typeof $ !== 'undefined' && $('#modalBajaGanancia').modal) {
            $('#modalBajaGanancia').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            var myModal = new bootstrap.Modal(document.getElementById('modalBajaGanancia'));
            myModal.show();
        }
    }
</script>
@endsection