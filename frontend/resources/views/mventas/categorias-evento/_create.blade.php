<div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('categorias-evento.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                    <i class="bi bi-plus-circle"></i>
                   Nueva Categoría de Evento
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label for="nom_categoria" class="form-label">
                            Nombre de la categoría
                        </label>

                        <input
                            type="text"
                            name="nom_categoria"
                            id="nom_categoria"
                            class="form-control"
                            value="{{ old('nom_categoria') }}"
                            maxlength="100"
                            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                            title="Solo se permiten letras y espacios."
                            oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g,'')"
                            required>

                    </div>

                    <div class="mb-3">

                        <label for="des_categoria" class="form-label">
                            Descripción
                        </label>

                        <textarea
                            name="des_categoria"
                            id="des_categoria"
                            class="form-control"
                            rows="4"
                            maxlength="255"
                            required>{{ old('des_categoria') }}</textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-success">
                        Guardar
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>