{{-- MODAL EDITAR BOLETO --}}
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
                        Editar Boleto

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row">

                        {{-- CÓDIGO DEL BOLETO --}}
                        <div class="col-md-6 mb-3">

                            <label for="edit_cod_boleto"
                                   class="form-label">

                                Código del Boleto
                            </label>

                            <input type="text"
                                   id="edit_cod_boleto"
                                   class="form-control"
                                   readonly>

                        </div>


                        {{-- EVENTO --}}
                        <div class="col-md-6 mb-3">

                            <label for="edit_cod_evento"
                                   class="form-label">

                                Evento
                            </label>

                            <select name="cod_evento"
                                    id="edit_cod_evento"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Seleccione un evento
                                </option>

                                @foreach($eventos as $evento)

                                    <option value="{{ $evento['COD_EVENTO'] }}">

                                        {{ $evento['NOM_EVENTO'] }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- TIPO DE BOLETO --}}
                        <div class="col-md-6 mb-3">

                            <label for="edit_tip_boleto"
                                   class="form-label">

                                Tipo de Boleto
                            </label>

                            <select name="tip_boleto"
                                    id="edit_tip_boleto"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Seleccione un tipo
                                </option>

                                <option value="VIP">
                                    VIP
                                </option>

                                <option value="GENERAL">
                                    General
                                </option>

                                <option value="PREFERENCIAL">
                                    Preferencial
                                </option>

                                <option value="BACKSTAGE">
                                    Backstage
                                </option>

                            </select>

                        </div>


                        {{-- PRECIO --}}
                        <div class="col-md-6 mb-3">

                            <label for="edit_mon_precio"
                                   class="form-label">

                                Precio
                            </label>

                            <input type="number"
                                   name="mon_precio"
                                   id="edit_mon_precio"
                                   class="form-control"
                                   min="0"
                                   step="0.01"
                                   placeholder="0.00"
                                   required>

                        </div>


                        {{-- CANTIDAD DISPONIBLE --}}
                        <div class="col-md-6 mb-3">

                            <label for="edit_num_disponible"
                                   class="form-label">

                                Cantidad Disponible
                            </label>

                            <input type="number"
                                   name="num_disponible"
                                   id="edit_num_disponible"
                                   class="form-control"
                                   min="0"
                                   placeholder="Ingrese la cantidad disponible"
                                   required>

                        </div>


                        {{-- DESCRIPCIÓN --}}
                        <div class="col-md-12 mb-3">

                            <label for="edit_des_boleto"
                                   class="form-label">

                                Descripción
                            </label>

                            <textarea name="des_boleto"
                                      id="edit_des_boleto"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Ingrese una descripción del boleto"
                                      required></textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        <i class="bi bi-x-circle"></i>
                        Cancelar

                    </button>


                    <button type="submit"
                            class="btn btn-warning">

                        <i class="bi bi-save"></i>
                        Actualizar Boleto

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>