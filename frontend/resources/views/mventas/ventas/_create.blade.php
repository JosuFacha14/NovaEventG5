<div class="modal fade"
     id="modalCrear"
     tabindex="-1"
     aria-labelledby="modalCrearLabel"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('ventas.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title"
                        id="modalCrearLabel">

                        <i class="bi bi-plus-circle"></i>

                        Nueva Venta

                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">

                            Cliente

                        </label>

                        <select
                            name="cod_cliente"
                            class="form-control"
                            required>

                            <option value="">
                                Seleccione un cliente
                            </option>

                            @foreach($clientes as $cliente)

                                <option value="{{ $cliente['COD_CLIENTE'] }}">

                                    Cliente #{{ $cliente['COD_CLIENTE'] }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Total

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            class="form-control"
                            name="mon_total"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Método de Pago

                        </label>

                        <select
                            name="metodo_pago"
                            class="form-control">

                            <option value="EFECTIVO">
                                EFECTIVO
                            </option>

                            <option value="TARJETA">
                                TARJETA
                            </option>

                            <option value="TRANSFERENCIA">
                                TRANSFERENCIA
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Estado

                        </label>

                        <select
                            name="estado_venta"
                            class="form-control">

                            <option value="PAGADA">
                                PAGADA
                            </option>

                            <option value="PENDIENTE">
                                PENDIENTE
                            </option>

                            <option value="CANCELADA">
                                CANCELADA
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        <i class="bi bi-save"></i>

                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>