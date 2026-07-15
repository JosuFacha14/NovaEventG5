{{-- MODAL CREAR BOLETO --}}
<div class="modal fade"
     id="modalCrear"
     tabindex="-1"
     aria-labelledby="modalCrearLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form action="{{ route('boletos.store') }}"
                  method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="modalCrearLabel">

                        <i class="bi bi-plus-circle"></i>
                        Nuevo Boleto

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row">

                        {{-- EVENTO --}}
                        <div class="col-md-6 mb-3">

                            <label for="cod_evento"
                                   class="form-label">

                                Evento
                            </label>

                            <select name="cod_evento"
                                    id="cod_evento"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Seleccione un evento
                                </option>

                                @foreach($eventos as $evento)

                                    <option value="{{ $evento['COD_EVENTO'] }}"
                                        {{ old('cod_evento') == $evento['COD_EVENTO'] ? 'selected' : '' }}>

                                        {{ $evento['NOM_EVENTO'] }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- TIPO DE BOLETO --}}
                        <div class="col-md-6 mb-3">

                            <label for="tip_boleto"
                                   class="form-label">

                                Tipo de Boleto
                            </label>

                            <select name="tip_boleto"
                                    id="tip_boleto"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Seleccione un tipo
                                </option>

                                <option value="VIP"
                                    {{ old('tip_boleto') == 'VIP' ? 'selected' : '' }}>
                                    VIP
                                </option>

                                <option value="GENERAL"
                                    {{ old('tip_boleto') == 'GENERAL' ? 'selected' : '' }}>
                                    General
                                </option>

                                <option value="PREFERENCIAL"
                                    {{ old('tip_boleto') == 'PREFERENCIAL' ? 'selected' : '' }}>
                                    Preferencial
                                </option>

                                <option value="BACKSTAGE"
                                    {{ old('tip_boleto') == 'BACKSTAGE' ? 'selected' : '' }}>
                                    Backstage
                                </option>

                            </select>

                        </div>


                        {{-- PRECIO --}}
                        <div class="col-md-6 mb-3">

                            <label for="mon_precio"
                                   class="form-label">

                                Precio
                            </label>

                            <input type="number"
                                   name="mon_precio"
                                   id="mon_precio"
                                   class="form-control"
                                   value="{{ old('mon_precio') }}"
                                   min="0"
                                   step="0.01"
                                   placeholder="0.00"
                                   required>

                        </div>


                        {{-- CANTIDAD DISPONIBLE --}}
                        <div class="col-md-6 mb-3">

                            <label for="num_disponible"
                                   class="form-label">

                                Cantidad Disponible
                            </label>

                            <input type="number"
                                   name="num_disponible"
                                   id="num_disponible"
                                   class="form-control"
                                   value="{{ old('num_disponible') }}"
                                   min="0"
                                   placeholder="Ingrese la cantidad disponible"
                                   required>

                        </div>


                        {{-- DESCRIPCIÓN --}}
                        <div class="col-md-12 mb-3">

                            <label for="des_boleto"
                                   class="form-label">

                                Descripción
                            </label>

                            <textarea name="des_boleto"
                                      id="des_boleto"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Ingrese una descripción del boleto"
                                      required>{{ old('des_boleto') }}</textarea>

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
                            class="btn btn-success">

                        <i class="bi bi-save"></i>
                        Guardar Boleto

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>