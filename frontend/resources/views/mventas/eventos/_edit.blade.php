{{-- MODAL EDITAR EVENTO --}}
<div class="modal fade"
     id="modalEditar"
     tabindex="-1"
     aria-labelledby="modalEditarLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form id="formEditar"
                  action=""
                  method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="modalEditarLabel">

                        <i class="bi bi-pencil-square"></i>
                        Editar Evento

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>


                <div class="modal-body">

                    {{-- CÓDIGO DEL EVENTO --}}
                    <input type="hidden"
                           id="edit_cod_evento"
                           name="cod_evento">


                    <div class="row">

                        {{-- CATEGORÍA --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Categoría
                            </label>

                            <select id="edit_cod_categoria"
                                    name="cod_categoria"
                                    class="form-control">

                                <option value="">
                                    Mantener categoría actual
                                </option>

                                @foreach($categorias as $categoria)

                                    <option value="{{ $categoria['COD_CATEGORIA'] }}">

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

                            <select id="edit_cod_ciclo_evento"
                                    name="cod_ciclo_evento"
                                    class="form-control">

                                <option value="">
                                    Mantener ciclo actual
                                </option>

                                @foreach($ciclos as $ciclo)

                                    <option value="{{ $ciclo['COD_CICLO_EVENTO'] }}">

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

                            <input type="number"
                                   id="edit_cod_reservacion"
                                   name="cod_reservacion"
                                   class="form-control"
                                   min="1"
                                   placeholder="Dejar vacío para mantener la actual">

                        </div>


                        {{-- NOMBRE --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nombre del Evento
                            </label>

                            <input type="text"
                                   id="edit_nom_evento"
                                   name="nom_evento"
                                   class="form-control"
                                   maxlength="150"
                                   required>

                        </div>


                        {{-- DESCRIPCIÓN --}}
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Descripción
                            </label>

                            <textarea id="edit_des_evento"
                                      name="des_evento"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Dejar vacío para mantener la descripción actual"></textarea>

                        </div>


                        {{-- FECHA --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Fecha del Evento
                            </label>

                            <input type="date"
                                   id="edit_fec_evento"
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
                                   id="edit_hor_evento"
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
                                   id="edit_des_lugar"
                                   name="des_lugar"
                                   class="form-control"
                                   maxlength="150"
                                   required>

                        </div>


                        {{-- CAPACIDAD --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Capacidad
                            </label>

                            <input type="number"
                                   id="edit_num_capacidad"
                                   name="num_capacidad"
                                   class="form-control"
                                   min="1"
                                   placeholder="Dejar vacío para mantener la capacidad actual">

                        </div>


                        {{-- ESTADO --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Estado del Evento
                            </label>

                            <select id="edit_ind_estado"
                                    name="ind_estado"
                                    class="form-control"
                                    required>

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
                            class="btn btn-warning">

                        <i class="bi bi-save"></i>
                        Actualizar Evento

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>