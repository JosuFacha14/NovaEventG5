{{-- MODAL CREAR CICLO DE EVENTO --}}
<div class="modal fade"
     id="modalCrearCiclo"
     tabindex="-1"
     aria-labelledby="modalCrearCicloLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form action="{{ route('ciclos-evento.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title"
                        id="modalCrearCicloLabel">

                        <i class="bi bi-plus-circle"></i>
                        Nuevo Ciclo de Evento

                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>

                <div class="modal-body">

                    {{-- NOMBRE DEL CICLO --}}
                    <div class="mb-3">

                        <label for="nom_ciclo"
                               class="form-label">

                            Nombre del Ciclo
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="nom_ciclo"
                               id="nom_ciclo"
                               class="form-control @error('nom_ciclo') is-invalid @enderror"
                               value="{{ old('nom_ciclo') }}"
                               maxlength="100"
                               placeholder="Ingrese el nombre del ciclo"
                               required>

                        @error('nom_ciclo')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DESCRIPCIÓN DEL CICLO --}}
                    <div class="mb-3">

                        <label for="des_ciclo"
                               class="form-label">

                            Descripción
                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="des_ciclo"
                                  id="des_ciclo"
                                  class="form-control @error('des_ciclo') is-invalid @enderror"
                                  rows="4"
                                  maxlength="255"
                                  placeholder="Ingrese una descripción del ciclo"
                                  required>{{ old('des_ciclo') }}</textarea>

                        @error('des_ciclo')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

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

                        <i class="bi bi-save"></i>
                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>