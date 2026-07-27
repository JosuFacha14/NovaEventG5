<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Editar Categoría de Evento
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nombre de la categoría
                        </label>

                        <input
                            type="text"
                            name="nom_categoria"
                            id="edit_nom_categoria"
                            class="form-control"
                            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                            title="Solo se permiten letras y espacios."
                            oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g,'')"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Descripción
                        </label>

                        <textarea
                            name="des_categoria"
                            id="edit_des_categoria"
                            class="form-control"
                            rows="4"
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
                        Actualizar
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>