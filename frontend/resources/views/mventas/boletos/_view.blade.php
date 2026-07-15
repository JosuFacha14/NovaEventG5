{{-- MODAL VER BOLETO --}}
<div class="modal fade"
     id="modalVer"
     tabindex="-1"
     aria-labelledby="modalVerLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="modalVerLabel">

                    <i class="bi bi-eye"></i>
                    Detalle del Boleto

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    {{-- CÓDIGO --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Código
                        </label>

                        <input type="text"
                               id="ver_cod_boleto"
                               class="form-control"
                               readonly>

                    </div>

                    {{-- EVENTO --}}
                    <div class="col-md-9 mb-3">

                        <label class="form-label">
                            Evento
                        </label>

                        <input type="text"
                               id="ver_nom_evento"
                               class="form-control"
                               readonly>

                    </div>

                    {{-- TIPO --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Tipo de boleto
                        </label>

                        <input type="text"
                               id="ver_tip_boleto"
                               class="form-control"
                               readonly>

                    </div>

                    {{-- PRECIO --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Precio
                        </label>

                        <input type="text"
                               id="ver_mon_precio"
                               class="form-control"
                               readonly>

                    </div>

                    {{-- DISPONIBLES --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Cantidad Disponible
                        </label>

                        <input type="text"
                               id="ver_num_disponible"
                               class="form-control"
                               readonly>

                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Descripción
                        </label>

                        <textarea id="ver_des_boleto"
                                  class="form-control"
                                  rows="4"
                                  readonly></textarea>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-circle"></i>
                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>