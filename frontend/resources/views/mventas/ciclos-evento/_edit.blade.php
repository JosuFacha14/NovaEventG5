{{-- MODAL EDITAR CICLO DE EVENTO --}}
<div class="modal fade"
     id="modalEditarCiclo"
     tabindex="-1"
     aria-labelledby="modalEditarCicloLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="formEditarCiclo"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header bg-warning">

                    <h5 class="modal-title"
                        id="modalEditarCicloLabel">

                        <i class="bi bi-pencil"></i>
                        Editar Ciclo de Evento

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>

                <div class="modal-body">

                    {{-- CÓDIGO --}}
                    <div class="mb-3">

                        <label for="edit_cod_ciclo"
                               class="form-label">
                            Código
                        </label>

                        <input type="text"
                               id="edit_cod_ciclo"
                               class="form-control"
                               readonly>

                    </div>


                    {{-- NOMBRE DEL CICLO --}}
                    <div class="mb-3">

                        <label for="edit_nom_ciclo"
                               class="form-label">

                            Nombre del Ciclo
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="nom_ciclo"
                               id="edit_nom_ciclo"
                               class="form-control"
                               maxlength="100"
                               required>

                    </div>


                    {{-- DESCRIPCIÓN --}}
                    <div class="mb-3">

                        <label for="edit_des_ciclo"
                               class="form-label">

                            Descripción
                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="des_ciclo"
                                  id="edit_des_ciclo"
                                  class="form-control"
                                  rows="4"
                                  maxlength="255"
                                  required></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button type="submit"
                            class="btn btn-warning">

                        <i class="bi bi-save"></i>
                        Actualizar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>