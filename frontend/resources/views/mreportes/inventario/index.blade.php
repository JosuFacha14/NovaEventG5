@extends('adminlte::page')

@section('title', 'Reporte de Inventario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Reporte de Uso de Inventario</h1>
        <button type="button" class="btn btn-info text-white" onclick="abrirModalInventario()">
            <i class="fas fa-plus-circle mr-1"></i> + Registrar Uso Inventario
        </button>
    </div>
@endsection

@section('content')
    <!-- Alerta de mensaje en la interfaz (sin utilizar alert() del navegador) -->
    <div id="alertaInterfaz" class="alert alert-danger d-none alert-dismissible fade show" role="alert">
        <span id="mensajeAlerta"></span>
        <button type="button" class="close text-white" onclick="cerrarAlerta()">&times;</button>
    </div>

    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-boxes mr-1"></i> Detalle de Materiales e Insumos por Evento</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="color: #ffffff !important; background-color: #343a40 !important;">Cód. Registro</th>
                            <th style="color: #ffffff !important; background-color: #343a40 !important;">Cód. Ítem</th>
                            <th style="color: #ffffff !important; background-color: #343a40 !important;">Cód. Evento</th>
                            <th style="color: #ffffff !important; background-color: #343a40 !important;">Cantidad Utilizada</th>
                            <th style="color: #ffffff !important; background-color: #343a40 !important;">Observaciones</th>
                            <th class="text-center" style="color: #ffffff !important; background-color: #343a40 !important;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventario as $inv)
                            @php
                                $codR = $inv->COD_REPORTE_INV ?? $inv->cod_reporte_inv ?? '-';
                                $codI = $inv->COD_ITEM ?? $inv->cod_item ?? '5';
                                $codE = $inv->COD_EVENTO ?? $inv->cod_evento ?? '1';
                                $cant = $inv->CAN_UTILIZADA ?? $inv->can_utilizada ?? 0;
                                $obs  = $inv->DES_OBSERVACIONES ?? $inv->des_observaciones ?? 'Sin observaciones';
                            @endphp
                            <tr>
                                <td class="align-middle" style="color: #212529 !important; font-weight: bold;">{{ $codR }}</td>
                                <td class="align-middle">
                                    <span style="background-color: #343a40 !important; color: #ffffff !important; padding: 5px 10px; border-radius: 4px; font-weight: bold; display: inline-block;">
                                        Ítem #{{ $codI }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span style="background-color: #007bff !important; color: #ffffff !important; padding: 5px 10px; border-radius: 4px; font-weight: bold; display: inline-block;">
                                        Evento #{{ $codE }}
                                    </span>
                                </td>
                                <td class="align-middle" style="color: #212529 !important;"><strong>{{ $cant }}</strong> un.</td>
                                <td class="align-middle" style="color: #212529 !important;">{{ $obs }}</td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-sm mr-1" title="Editar" style="background-color: #ffc107 !important; color: #000000 !important; border: 1px solid #d39e00 !important; padding: 4px 8px;">
                                        <i class="fas fa-edit" style="color: #000000 !important;"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm" onclick="confirmarBajaInventario('{{ $codR }}')" title="Dar de baja" style="background-color: #dc3545 !important; color: #ffffff !important; border: 1px solid #bd2130 !important; padding: 4px 8px;">
                                        <i class="fas fa-user-minus" style="color: #ffffff !important;"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">No hay registros de inventario.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Registrar / Añadir Datos desde Interfaz -->
    <div class="modal fade" id="modalCrearInventario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="fas fa-plus-circle text-info"></i> Registrar Uso de Inventario</h5>
                    <button type="button" class="close text-white" onclick="cerrarModalManual('modalCrearInventario')">&times;</button>
                </div>
                <form id="formCrearInventario">
                    @csrf
                    <div class="modal-body">
                        <div id="modalErrorAlerta" class="alert alert-danger d-none"></div>
                        
                        <div class="form-group mb-3">
                            <label>Seleccionar Ítem/Producto</label>
                            <select id="cod_item" name="cod_item" class="form-control bg-secondary text-white" required>
                                @if(isset($items) && count($items) > 0)
                                    @foreach($items as $item)
                                        <option value="{{ $item->COD_ITEM ?? $item->cod_item }}">
                                            Ítem #{{ $item->COD_ITEM ?? $item->cod_item }} - {{ $item->NOM_ITEM ?? $item->DES_ITEM ?? 'Insumo' }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="5" selected>Ítem #5 (Existente)</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Seleccionar Evento</label>
                            <select id="cod_evento" name="cod_evento" class="form-control bg-secondary text-white" required>
                                @if(isset($eventos) && count($eventos) > 0)
                                    @foreach($eventos as $evento)
                                        <option value="{{ $evento->COD_EVENTO ?? $evento->cod_evento }}">
                                            Evento #{{ $evento->COD_EVENTO ?? $evento->cod_evento }} - {{ $evento->NOM_EVENTO ?? 'Evento' }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="1" selected>Evento #1 (Existente)</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Cantidad Utilizada</label>
                            <input type="number" id="can_utilizada" name="can_utilizada" class="form-control bg-secondary text-white" required placeholder="0">
                        </div>
                        <div class="form-group mb-3">
                            <label>Observaciones</label>
                            <textarea id="des_observaciones" name="des_observaciones" class="form-control bg-secondary text-white" rows="3" placeholder="Comentarios opcionales..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" onclick="cerrarModalManual('modalCrearInventario')">Cancelar</button>
                        <button type="button" id="btnGuardarInventario" onclick="ejecutarGuardadoInventario()" class="btn btn-info">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Baja (Mismo estilo gráfico de la primera imagen) -->
    <div class="modal fade" id="modalBajaInventario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-dark text-white border-0">
                <div class="modal-header bg-danger text-white py-2">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Confirmar baja</h5>
                    <button type="button" class="close text-white" onclick="cerrarModalManual('modalBajaInventario')">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="txtBajaInventario" class="mb-2 font-weight-bold"></p>
                    <small class="text-muted">Esta acción desactivará el registro. No se eliminarán los datos permanentemente.</small>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary px-4" onclick="cerrarModalManual('modalBajaInventario')">Cancelar</button>
                    <button type="button" class="btn btn-danger px-4" onclick="ejecutarBajaProceso()"><i class="fas fa-user-minus mr-1"></i> Dar de baja</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    var guardandoInventarioProceso = false;
    var registroSeleccionadoId = null;

    // Abrir modal asegurando compatibilidad total
    function abrirModalInventario() {
        const modalEl = document.getElementById('modalCrearInventario');
        const errModal = document.getElementById('modalErrorAlerta');
        
        if (errModal) errModal.classList.add('d-none');

        if (window.jQuery && typeof $('#modalCrearInventario').modal === 'function') {
            $('#modalCrearInventario').modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var myModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            myModal.show();
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            
            if (!document.querySelector('.modal-backdrop')) {
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'customBackdrop';
                document.body.appendChild(backdrop);
            }
        }
    }

    // Cerrar modal manualmente si fallara la API de jQuery/Bootstrap
    function cerrarModalManual(idModal) {
        const modalEl = document.getElementById(idModal);
        if (window.jQuery && typeof $(modalEl).modal === 'function') {
            $(modalEl).modal('hide');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var myModal = bootstrap.Modal.getInstance(modalEl);
            if (myModal) myModal.hide();
        }
        
        // Cierre defensivo CSS
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        modalEl.setAttribute('aria-hidden', 'true');
        var backdrop = document.getElementById('customBackdrop');
        if (backdrop) backdrop.remove();
        document.body.classList.remove('modal-open');
    }

    function mostrarErrorUI(mensaje) {
        const errModal = document.getElementById('modalErrorAlerta');
        if (errModal) {
            errModal.innerText = mensaje;
            errModal.classList.remove('d-none');
        } else {
            const alertDiv = document.getElementById('alertaInterfaz');
            document.getElementById('mensajeAlerta').innerText = mensaje;
            alertDiv.classList.remove('d-none');
        }
    }

    function cerrarAlerta() {
        document.getElementById('alertaInterfaz').classList.add('d-none');
    }

    function ejecutarGuardadoInventario() {
        if (guardandoInventarioProceso) return;

        const btn = document.getElementById('btnGuardarInventario');
        const form = document.getElementById('formCrearInventario');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        guardandoInventarioProceso = true;
        if (btn) btn.disabled = true;

        const formData = new FormData(form);

        fetch("{{ route('inventario.store') }}", {
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
                mostrarErrorUI(data.message || data.msg || 'Error al guardar. Verifique la base de datos.');
                guardandoInventarioProceso = false;
                if (btn) btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarErrorUI('Ocurrió un error en la solicitud.');
            guardandoInventarioProceso = false;
            if (btn) btn.disabled = false;
        });
    }

    function confirmarBajaInventario(id) {
        registroSeleccionadoId = id;
        document.getElementById('txtBajaInventario').innerText = `¿Está seguro de que desea dar de baja el registro de inventario #${id}?`;
        
        const modalEl = document.getElementById('modalBajaInventario');
        if (window.jQuery && typeof $('#modalBajaInventario').modal === 'function') {
            $('#modalBajaInventario').modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var myModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            myModal.show();
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            if (!document.querySelector('.modal-backdrop')) {
                var backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'customBackdrop';
                document.body.appendChild(backdrop);
            }
        }
    }

    function ejecutarBajaProceso() {
        location.reload();
    }
</script>
@endsection