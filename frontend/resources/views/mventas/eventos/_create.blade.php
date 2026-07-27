{{-- MODAL CREAR EVENTO --}}
<div class="modal fade"
     id="modalCrear"
     tabindex="-1"
     aria-labelledby="modalCrearLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form action="{{ route('eventos.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="modalCrearLabel">

                        <i class="bi bi-calendar-plus"></i>
                        Nuevo Evento

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row">

                        {{-- CATEGORÍA --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Categoría
                            </label>

                            <select name="cod_categoria"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Seleccione una categoría
                                </option>

                                @foreach($categorias as $categoria)

                                    <option
                                        value="{{ $categoria['COD_CATEGORIA'] }}">

                                        {{ $categoria['NOM_CATEGORIA'] }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- CICLO --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Ciclo de Evento
                            </label>

                            <select name="cod_ciclo_evento"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Seleccione un ciclo
                                </option>

                                @foreach($ciclos as $ciclo)

                                    <option
                                        value="{{ $ciclo['COD_CICLO_EVENTO'] }}">

                                        {{ $ciclo['NOM_CICLO'] }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- RESERVACIÓN --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Código de Reservación
                            </label>

                            <select name="cod_reservacion"
        class="form-control"
        required>

    <option value="" selected disabled>
        Seleccione una reservación
    </option>

    @foreach($reservaciones as $reservacion)

        <option value="{{ $reservacion['COD_RESERVACION'] }}">
            {{ $reservacion['COD_RESERVACION'] }}
        </option>

    @endforeach

</select>

                        </div>


                        {{-- NOMBRE --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nombre del Evento
                            </label>

                            <input type="text"
                                 name="nom_evento"
                                 class="form-control"
                                 maxlength="150"
                                 pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                                 oninput="this.value=this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g,'')"
                                 placeholder="Ingrese el nombre del evento"
                                required>

                        </div>


                        {{-- DESCRIPCIÓN --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Descripción
                            </label>

                            <textarea name="des_evento"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Ingrese la descripción del evento"
                                      required></textarea>

                        </div>


                        {{-- FECHA --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Fecha del Evento
                            </label>

                            <input type="date"
                                   name="fec_evento"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- HORA --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Hora del Evento
                            </label>

                            <input type="time"
                                   name="hor_evento"
                                   class="form-control"
                                   required>

                        </div>


                        {{-- LUGAR --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Lugar
                            </label>

                            <input type="text"
                                   name="des_lugar"
                                   class="form-control"
                                   maxlength="150"
                                   placeholder="Ingrese el lugar del evento"
                                   required>

                        </div>


                        {{-- CAPACIDAD --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Capacidad
                            </label>

                            <input type="number"
                                   name="num_capacidad"
                                   class="form-control"
                                   min="1"
                                   placeholder="Ingrese la capacidad"
                                   required>

                        </div>


                        {{-- ESTADO --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Estado del Evento
                            </label>

                            <select name="ind_estado"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Seleccione un estado
                                </option>

                                <option value="ACTIVO">
                                    Activo
                                </option>

                                <option value="CANCELADO">
                                    Cancelado
                                </option>

                                <option value="FINALIZADO">
                                    Finalizado
                                </option>

                            </select>

                        </div>

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
                        Guardar Evento

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>