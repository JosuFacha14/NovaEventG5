<div class="modal fade"
     id="modalEditar"
     tabindex="-1"
     aria-labelledby="modalEditarLabel"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                id="formEditar"
                method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header bg-warning">

                    <h5 class="modal-title">

                        <i class="bi bi-pencil"></i>

                        Editar Venta

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        id="edit_cod_venta">

                    <div class="mb-3">

                        <label>Cliente</label>

                        <select
                            name="cod_cliente"
                            id="edit_cod_cliente"
                            class="form-control"
                            required>

                            @foreach($clientes as $cliente)

                                <option
                                    value="{{ $cliente['COD_CLIENTE'] }}">

                                    {{ $cliente['COD_CLIENTE'] }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Total</label>

                        <input
                            type="number"
                            step="0.01"
                            name="mon_total"
                            id="edit_mon_total"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Método Pago</label>

                        <select
                            name="metodo_pago"
                            id="edit_metodo_pago"
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

                        <label>Estado</label>

                        <select
                            name="estado_venta"
                            id="edit_estado_venta"
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
                        type="submit"
                        class="btn btn-warning">

                        Actualizar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>