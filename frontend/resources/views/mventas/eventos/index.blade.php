@extends('adminlte::page')

@section('title', 'Gestión de Eventos')

@section('content_header')
    <h1>Gestión de Eventos</h1>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Se encontraron los siguientes errores:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-primary">

    <div class="card-header">

        <h3 class="card-title">
            Eventos Registrados
        </h3>

        <div class="card-tools">

            <button type="button"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCrear">

                <i class="bi bi-plus-lg"></i>
                Nuevo Evento

            </button>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Evento</th>
                        <th>Categoría</th>
                        <th>Ciclo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Lugar</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($eventos as $evento)

                    <tr>

                        <td>{{ $evento['COD_EVENTO'] ?? '' }}</td>

                        <td>{{ $evento['NOM_EVENTO'] ?? '' }}</td>

                        <td>
                            {{ $evento['NOM_CATEGORIA'] ?? 'No disponible' }}
                        </td>

                        <td>
                            {{ $evento['NOM_CICLO'] ?? 'No disponible' }}
                        </td>

                        <td>{{ $evento['FEC_EVENTO'] ?? '' }}</td>

                        <td>{{ $evento['HOR_EVENTO'] ?? '' }}</td>

                        <td>{{ $evento['DES_LUGAR'] ?? '' }}</td>

                        <td>

                            @php
                                $estado = $evento['IND_ESTADO'] ?? '';
                            @endphp

                            @if($estado == 'ACTIVO')

                                <span class="badge bg-success">
                                    Activo
                                </span>

                            @elseif($estado == 'CANCELADO')

                                <span class="badge bg-danger">
                                    Cancelado
                                </span>

                            @elseif($estado == 'FINALIZADO')

                                <span class="badge bg-secondary">
                                    Finalizado
                                </span>

                            @else

                                <span class="badge bg-info">
                                    {{ $estado ?: 'Sin estado' }}
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            {{-- VER --}}
                            <button type="button"
                                    class="btn btn-info btn-sm btnVer"
                                    title="Ver evento"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalVer"
                                    data-id="{{ $evento['COD_EVENTO'] ?? '' }}"
                                    data-nombre="{{ $evento['NOM_EVENTO'] ?? '' }}"
                                    data-categoria="{{ $evento['NOM_CATEGORIA'] ?? 'No disponible' }}"
                                    data-ciclo="{{ $evento['NOM_CICLO'] ?? 'No disponible' }}"
                                    data-fecha="{{ $evento['FEC_EVENTO'] ?? '' }}"
                                    data-hora="{{ $evento['HOR_EVENTO'] ?? '' }}"
                                    data-lugar="{{ $evento['DES_LUGAR'] ?? '' }}"
                                    data-estado="{{ $evento['IND_ESTADO'] ?? '' }}">

                                <i class="bi bi-eye"></i>

                            </button>


                            {{-- EDITAR --}}
                            <button type="button"
                                    class="btn btn-warning btn-sm btnEditar"
                                    title="Editar evento"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-id="{{ $evento['COD_EVENTO'] ?? '' }}"
                                    data-nombre="{{ $evento['NOM_EVENTO'] ?? '' }}"
                                    data-fecha="{{ $evento['FEC_EVENTO'] ?? '' }}"
                                    data-hora="{{ $evento['HOR_EVENTO'] ?? '' }}"
                                    data-lugar="{{ $evento['DES_LUGAR'] ?? '' }}"
                                    data-estado="{{ $evento['IND_ESTADO'] ?? '' }}">

                                <i class="bi bi-pencil"></i>

                            </button>


                            {{-- BAJA LÓGICA --}}
                            <button type="button"
                                    class="btn btn-danger btn-sm btnEliminar"
                                    title="Dar de baja"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar"
                                    data-id="{{ $evento['COD_EVENTO'] ?? '' }}"
                                    data-nombre="{{ $evento['NOM_EVENTO'] ?? '' }}">

                                <i class="bi bi-person-dash"></i>

                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center">
                            No existen eventos registrados.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- MODAL CREAR --}}
@include('mventas.eventos._create')


{{-- MODAL EDITAR --}}
@include('mventas.eventos._edit')


{{-- MODAL VER --}}
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
                    Detalle del Evento
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Código
                        </label>

                        <input type="text"
                               id="ver_cod_evento"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Nombre del Evento
                        </label>

                        <input type="text"
                               id="ver_nom_evento"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Categoría
                        </label>

                        <input type="text"
                               id="ver_categoria"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Ciclo
                        </label>

                        <input type="text"
                               id="ver_ciclo"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Fecha
                        </label>

                        <input type="text"
                               id="ver_fec_evento"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Hora
                        </label>

                        <input type="text"
                               id="ver_hor_evento"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Lugar
                        </label>

                        <input type="text"
                               id="ver_des_lugar"
                               class="form-control"
                               readonly>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Estado
                        </label>

                        <input type="text"
                               id="ver_ind_estado"
                               class="form-control"
                               readonly>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>


{{-- MODAL CONFIRMAR BAJA --}}
<div class="modal fade"
     id="modalEliminar"
     tabindex="-1"
     aria-labelledby="modalEliminarLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title"
                    id="modalEliminarLabel">

                    <i class="bi bi-exclamation-triangle"></i>
                    Confirmar baja

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    ¿Está seguro de que desea dar de baja el evento
                    <strong id="nombreEventoEliminar"></strong>?
                </p>

                <small class="text-muted">
                    Esta acción desactivará el evento.
                    No se eliminarán los datos.
                </small>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Cancelar

                </button>

                <form id="formEliminar"
                      action=""
                      method="POST"
                      class="d-inline">

                    @csrf
                    @method('PUT')

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="bi bi-person-dash"></i>
                        Dar de baja

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@stop


@section('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | VER EVENTO
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnVer').forEach(function (boton) {

        boton.addEventListener('click', function () {

            document.getElementById('ver_cod_evento').value =
                this.getAttribute('data-id');

            document.getElementById('ver_nom_evento').value =
                this.getAttribute('data-nombre');

            document.getElementById('ver_categoria').value =
                this.getAttribute('data-categoria');

            document.getElementById('ver_ciclo').value =
                this.getAttribute('data-ciclo');

            document.getElementById('ver_fec_evento').value =
                this.getAttribute('data-fecha');

            document.getElementById('ver_hor_evento').value =
                this.getAttribute('data-hora');

            document.getElementById('ver_des_lugar').value =
                this.getAttribute('data-lugar');

            const estado =
                this.getAttribute('data-estado');

            let estadoTexto = estado;

            if (estado === 'ACTIVO') {
                estadoTexto = 'Activo';
            }

            if (estado === 'CANCELADO') {
                estadoTexto = 'Cancelado';
            }

            if (estado === 'FINALIZADO') {
                estadoTexto = 'Finalizado';
            }

            document.getElementById('ver_ind_estado').value =
                estadoTexto;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EDITAR EVENTO
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEditar').forEach(function (boton) {

        boton.addEventListener('click', function () {

            const id =
                this.getAttribute('data-id');

            document.getElementById('edit_cod_evento').value =
                id;

            document.getElementById('edit_nom_evento').value =
                this.getAttribute('data-nombre');

            document.getElementById('edit_fec_evento').value =
                this.getAttribute('data-fecha');

            const hora =
                this.getAttribute('data-hora') || '';

            document.getElementById('edit_hor_evento').value =
                hora.substring(0, 5);

            document.getElementById('edit_des_lugar').value =
                this.getAttribute('data-lugar');

            document.getElementById('edit_ind_estado').value =
                this.getAttribute('data-estado');

            document.getElementById('edit_cod_categoria').value =
                '';

            document.getElementById('edit_cod_ciclo_evento').value =
                '';

            document.getElementById('edit_cod_reservacion').value =
                '';

            document.getElementById('edit_des_evento').value =
                '';

            document.getElementById('edit_num_capacidad').value =
                '';

            let url =
                "{{ route('eventos.update', ['id' => '__ID__']) }}";

            url =
                url.replace('__ID__', id);

            document.getElementById('formEditar').action =
                url;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR BAJA LÓGICA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEliminar').forEach(function (boton) {

        boton.addEventListener('click', function () {

            const id =
                this.getAttribute('data-id');

            const nombre =
                this.getAttribute('data-nombre');

            document.getElementById(
                'nombreEventoEliminar'
            ).textContent = nombre;

            let url =
                "{{ route('eventos.destroy', ['id' => '__ID__']) }}";

            url =
                url.replace('__ID__', id);

            document.getElementById(
                'formEliminar'
            ).action = url;

        });

    });

});

</script>

@stop