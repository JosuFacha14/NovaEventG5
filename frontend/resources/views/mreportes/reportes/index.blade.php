@extends('adminlte::page')

@section('title', 'Panel de Reportes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Panel General de Reportes</h1>
        <!-- Botón para añadir un nuevo registro desde la interfaz -->
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearReporte">
            <i class="fas fa-plus-circle mr-1"></i> + Nuevo Reporte
        </button>
    </div>
@endsection

@section('content')
    <!-- Tarjetas Superiores (Small Boxes) -->
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $datos['total_eventos'] ?? 0 }}</h3>
                    <p>Eventos Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('reportes.ganancias') }}" class="small-box-footer">
                    Ver Ganancias <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>L. {{ number_format($datos['total_ganancias'] ?? 0, 2) }}</h3>
                    <p>Ingresos Totales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('reportes.ganancias') }}" class="small-box-footer">
                    Ver Ganancias <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>L. {{ number_format($datos['total_costos'] ?? 0, 2) }}</h3>
                    <p>Costos Operativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('reportes.costos') }}" class="small-box-footer">
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
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Título / Concepto</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($datos['reportes_lista'] ?? [] as $reporte)
                            <tr>
                                <td>{{ $reporte['id'] }}</td>
                                <td>{{ $reporte['concepto'] ?? 'Reporte' }}</td>
                                <td>L. {{ number_format($reporte['monto'] ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ ($reporte['estado'] ?? 'Activo') == 'Activo' ? 'success' : 'secondary' }}">
                                        {{ $reporte['estado'] ?? 'Activo' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!-- Botón Editar -->
                                    <button class="btn btn-warning btn-sm" onclick="abrirModalEditar('{{ $reporte['id'] }}', '{{ $reporte['concepto'] ?? '' }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Botón Dar de Baja (Abre Modal de la interfaz) -->
                                    <button class="btn btn-danger btn-sm" onclick="confirmarBaja('{{ $reporte['id'] }}', '{{ $reporte['concepto'] ?? 'este reporte' }}')">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">
                                    No hay reportes registrados aún. Usa el botón <b>"+ Nuevo Reporte"</b> para agregar uno.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal 1: Añadir Nuevo Reporte -->
    <div class="modal fade" id="modalCrearReporte" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="fas fa-plus-circle text-primary mr-1"></i> Registrar Nuevo Reporte</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCrearReporte">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Concepto / Nombre del Reporte</label>
                            <input type="text" class="form-control bg-secondary text-white" id="conceptoReporte" required placeholder="Ej. Reporte mensual de ganancias">
                        </div>
                        <div class="form-group">
                            <label>Monto (L.)</label>
                            <input type="number" step="0.01" class="form-control bg-secondary text-white" id="montoReporte" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 2: Confirmar Baja  -->
    <div class="modal fade" id="modalConfirmarBaja" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmar baja</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="mensajeConfirmacionBaja">¿Está seguro de que desea dar de baja este reporte?</p>
                    <small class="text-muted">Esta acción desactivará el registro en la interfaz.</small>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnEjecutarBaja"><i class="fas fa-user-minus"></i> Dar de baja</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    let reporteIdSeleccionado = null;

    
    function confirmarBaja(id, concepto) {
        reporteIdSeleccionado = id;
        document.getElementById('mensajeConfirmacionBaja').innerText = `¿Está seguro de que desea dar de baja el reporte "${concepto}"?`;
        $('#modalConfirmarBaja').modal('show');
    }

    
    document.getElementById('btnEjecutarBaja').addEventListener('click', function() {
        $('#modalConfirmarBaja').modal('hide');
        
        
        console.log("Enviando baja a la API para ID:", reporteIdSeleccionado);
       
    });
</script>
@endsection