<div class="modal fade"
     id="modalVer"
     tabindex="-1"
     aria-labelledby="modalVerLabel"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-info text-white">

                <h5 class="modal-title"
                    id="modalVerLabel">

                    <i class="bi bi-eye"></i>

                    Información de la Venta

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <label>Código</label>

                    <input
                        type="text"
                        id="ver_cod_venta"
                        class="form-control"
                        readonly>

                </div>

                <div class="mb-3">

                    <label>Cliente</label>

                    <input
                        type="text"
                        id="ver_cod_cliente"
                        class="form-control"
                        readonly>

                </div>

                <div class="mb-3">

                    <label>Total</label>

                    <input
                        type="text"
                        id="ver_mon_total"
                        class="form-control"
                        readonly>

                </div>

                <div class="mb-3">

                    <label>Método de Pago</label>

                    <input
                        type="text"
                        id="ver_metodo_pago"
                        class="form-control"
                        readonly>

                </div>

                <div class="mb-3">

                    <label>Estado</label>

                    <input
                        type="text"
                        id="ver_estado"
                        class="form-control"
                        readonly>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>