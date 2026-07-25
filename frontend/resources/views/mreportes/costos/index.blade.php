@extends('adminlte::page')

@section('title', 'Costos Operativos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Reporte de Costos Operativos</h1>
        <!-- Soporte para Bootstrap 4, Bootstrap 5 y Trigger JS manual -->
        <button type="button" class="btn btn-primary" 
                data-toggle="modal" data-target="#modalCrearCosto"
                data-bs-toggle="modal" data-bs-target="#modalCrearCosto"
                onclick="abrirModalCrear()">
            <i class="fas fa-plus-circle mr-1"></i> + Registrar Costo
        </button>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="info-box bg-light elevation-1">
                <span class="info-box-icon bg-info"><i class="fas fa-calculator"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Presupuestado</span>
                    <span class="info-box-number text-lg">L. {{ number_format($totalPresupuestado ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box bg-light elevation-1">
                <span class="info-box-icon bg-danger"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Real Ejecutado</span>
                    <span class="info-box-number text-lg">L. {{ number_format($totalReal ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Detalle de Costos por Evento</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Cód. Costo</th>
                            <th>Cód. Evento</th>
                            <th>Monto Presupuestado</th>
                            <th>Monto Real</th>
                            <th>Desviación</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costos as $c)
                            @php
                                $pres = $c->MON_PRESUPUESTADO ?? $c->mon_presupuestado ?? 0;
                                $real = $c->MON_REAL ?? $c->mon_real ?? 0;
                                $desviacion = $real - $pres;
                                $codCosto = $c->COD_COSTO ?? $c->cod_costo ?? 1;
                            @endphp
                            <tr>
                                <td>{{ $codCosto }}</td>
                                <td><span class="badge badge-info">Evento #{{ $c->COD_EVENTO ?? $c->cod_evento ?? 1 }}</span></td>
                                <td>L. {{ number_format($pres, 2) }}</td>
                                <td>L. {{ number_format($real, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $desviacion <= 0 ? 'success' : 'danger' }}">
                                        L. {{ number_format($desviacion, 2) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-warning btn-sm mr-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarBajaCosto('{{ $codCosto }}')" title="Dar de baja">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-3 text-muted">No hay registros de costos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Costo -->
    <div class="modal fade" id="modalCrearCosto" tabindex="-1" role="dialog" aria-labelledby="modalCrearCostoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="modalCrearCostoLabel"><i class="fas fa-plus-circle text-primary"></i> Registrar Nuevo Costo</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCrearCosto">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Código Evento</label>
                            <input type="number" id="cod_evento" name="cod_evento" class="form-control bg-secondary text-white" required placeholder="Ej. 1">
                        </div>
                        <div class="form-group mb-3">
                            <label>Monto Presupuestado (L.)</label>
                            <input type="number" step="0.01" id="mon_presupuestado" name="mon_presupuestado" class="form-control bg-secondary text-white" required placeholder="0.00">
                        </div>
                        <div class="form-group mb-3">
                            <label>Monto Real (L.)</label>
                            <input type="number" step="0.01" id="mon_real" name="mon_real" class="form-control bg-secondary text-white" required placeholder="0.00">
                        </div>
                        <div class="form-group mb-3">
                            <label>Categoría</label>
                            <input type="text" id="ind_categoria" name="ind_categoria" class="form-control bg-secondary text-white" value="OPERATIVO" required>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Baja -->
    <div class="modal fade" id="modalBajaCosto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmar baja</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="txtBajaCosto">¿Está seguro de que desea dar de baja este registro de costo?</p>
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
    // Función forzada de apertura por JS si fallan los data-attributes
    function abrirModalCrear() {
        if (typeof $ !== 'undefined' && $('#modalCrearCosto').modal) {
            $('#modalCrearCosto').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            var myModal = new bootstrap.Modal(document.getElementById('modalCrearCosto'));
            myModal.show();
        } else {
            var el = document.getElementById('modalCrearCosto');
            el.classList.add('show');
            el.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('formCrearCosto');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch("{{ route('costos.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof $ !== 'undefined' && $('#modalCrearCosto').modal) {
                            $('#modalCrearCosto').modal('hide');
                        }
                        location.reload();
                    } else {
                        console.error('Error al guardar:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error de red:', error);
                });
            });
        }
    });

    function confirmarBajaCosto(id) {
        document.getElementById('txtBajaCosto').innerText = `¿Está seguro de que desea dar de baja el Costo #${id}?`;
        if (typeof $ !== 'undefined' && $('#modalBajaCosto').modal) {
            $('#modalBajaCosto').modal('show');
        } else if (typeof bootstrap !== 'undefined') {
            var myModal = new bootstrap.Modal(document.getElementById('modalBajaCosto'));
            myModal.show();
        }
    }
</script>
@endsection