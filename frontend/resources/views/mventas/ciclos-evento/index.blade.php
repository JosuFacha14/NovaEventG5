@extends('adminlte::page')

@section('title', 'Ciclos de Evento')

@section('content_header')
<div class="row align-items-center">
    <div class="col-sm-6">
        <h3 class="mb-0">Catálogo de Ciclos de Evento</h3>
    </div>

    <div class="col-sm-6 text-end">
        <button
            class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalCrearCiclo">

            <i class="bi bi-plus-lg me-1"></i>
            Nuevo Ciclo

        </button>
    </div>
</div>
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

<div class="card card-primary">

    <div class="card-header">

    <h3 class="card-title">
    <i class="bi bi-arrow-repeat me-2"></i>
    Ciclos de Evento Registrados
</h3>

</div>


    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>

                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>

            </thead>


            <tbody>

            @forelse($ciclos as $ciclo)

                <tr>

                    <td>
                        {{ $ciclo['COD_CICLO_EVENTO'] ?? '' }}
                    </td>

                    <td>
                        {{ $ciclo['NOM_CICLO'] ?? '' }}
                    </td>

                    <td>
                        {{ $ciclo['DES_CICLO'] ?? '' }}
                    </td>

                    <td>

                        @if(($ciclo['IND_ACTIVO'] ?? '0') == '1')

                            <span class="badge bg-success">
                                Activo
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Inactivo
                            </span>

                        @endif

                    </td>


                    <td class="text-center">

                        {{-- VER --}}
                        <button type="button"
                                class="btn btn-info btn-sm btnVerCiclo"
                                title="Ver ciclo"
                                data-bs-toggle="modal"
                                data-bs-target="#modalVerCiclo"
                                data-id="{{ $ciclo['COD_CICLO_EVENTO'] ?? '' }}"
                                data-nombre="{{ $ciclo['NOM_CICLO'] ?? '' }}"
                                data-descripcion="{{ $ciclo['DES_CICLO'] ?? '' }}"
                                data-estado="{{ $ciclo['IND_ACTIVO'] ?? '0' }}">

                            <i class="bi bi-eye"></i>

                        </button>


                        {{-- EDITAR --}}
                        <button type="button"
                                class="btn btn-warning btn-sm btnEditarCiclo"
                                title="Editar ciclo"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarCiclo"
                                data-id="{{ $ciclo['COD_CICLO_EVENTO'] ?? '' }}"
                                data-nombre="{{ $ciclo['NOM_CICLO'] ?? '' }}"
                                data-descripcion="{{ $ciclo['DES_CICLO'] ?? '' }}">

                            <i class="bi bi-pencil"></i>

                        </button>


                        {{-- BAJA LÓGICA --}}
                        <button type="button"
                                class="btn btn-danger btn-sm btnBajaCiclo"
                                title="Dar de baja"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBajaCiclo"
                                data-id="{{ $ciclo['COD_CICLO_EVENTO'] ?? '' }}"
                                data-nombre="{{ $ciclo['NOM_CICLO'] ?? '' }}">

                            <i class="bi bi-person-dash"></i>

                        </button>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5"
                        class="text-center">

                        No existen ciclos de evento registrados.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- MODAL CREAR --}}
@include('mventas.ciclos-evento._create')


{{-- MODAL EDITAR --}}
@include('mventas.ciclos-evento._edit')


{{-- =========================================================
     MODAL VER CICLO
========================================================= --}}

<div class="modal fade"
     id="modalVerCiclo"
     tabindex="-1"
     aria-labelledby="modalVerCicloLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"
                    id="modalVerCicloLabel">

                    <i class="bi bi-eye"></i>
                    Detalle del Ciclo de Evento

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
                        Código
                    </label>

                    <input type="text"
                           id="ver_ciclo_id"
                           class="form-control"
                           readonly>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input type="text"
                           id="ver_ciclo_nombre"
                           class="form-control"
                           readonly>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Descripción
                    </label>

                    <textarea id="ver_ciclo_descripcion"
                              class="form-control"
                              rows="4"
                              readonly></textarea>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Estado
                    </label>

                    <input type="text"
                           id="ver_ciclo_estado"
                           class="form-control"
                           readonly>

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


{{-- =========================================================
     MODAL CONFIRMAR BAJA
========================================================= --}}

<div class="modal fade"
     id="modalBajaCiclo"
     tabindex="-1"
     aria-labelledby="modalBajaCicloLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title"
                    id="modalBajaCicloLabel">

                    <i class="bi bi-exclamation-triangle"></i>
                    Confirmar baja

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>


            <form id="formBajaCiclo"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="modal-body">

                    <p>

                        ¿Está seguro de que desea dar de baja el ciclo

                        <strong id="baja_ciclo_nombre"></strong>?

                    </p>


                    <small class="text-muted">

                        Esta acción desactivará el ciclo de evento.
                        No se eliminarán los datos.

                    </small>

                </div>


                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancelar

                    </button>


                    <button type="submit"
                            class="btn btn-danger">

                        <i class="bi bi-person-dash"></i>
                        Dar de baja

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@stop


@section('js')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | VER CICLO
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnVerCiclo').forEach(function (boton) {

        boton.addEventListener('click', function () {

            document.getElementById('ver_ciclo_id').value =
                this.getAttribute('data-id');

            document.getElementById('ver_ciclo_nombre').value =
                this.getAttribute('data-nombre');

            document.getElementById('ver_ciclo_descripcion').value =
                this.getAttribute('data-descripcion');

            document.getElementById('ver_ciclo_estado').value =
                this.getAttribute('data-estado') === '1'
                    ? 'Activo'
                    : 'Inactivo';

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EDITAR CICLO
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEditarCiclo').forEach(function (boton) {

        boton.addEventListener('click', function () {

            const id = this.getAttribute('data-id');

document.getElementById('edit_nom_ciclo').value =
    this.getAttribute('data-nombre');

document.getElementById('edit_des_ciclo').value =
    this.getAttribute('data-descripcion');

let url =
    "{{ route('ciclos-evento.update', ['id' => '__ID__']) }}";

url = url.replace('__ID__', id);

document.getElementById('formEditarCiclo').action =
    url;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR BAJA LÓGICA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnBajaCiclo').forEach(function (boton) {

        boton.addEventListener('click', function () {

            const id =
                this.getAttribute('data-id');

            const nombre =
                this.getAttribute('data-nombre');


            document.getElementById(
                'baja_ciclo_nombre'
            ).textContent = nombre;


            let url =
                "{{ route('ciclos-evento.destroy', ['id' => '__ID__']) }}";

            url = url.replace('__ID__', id);


            document.getElementById(
                'formBajaCiclo'
            ).action = url;

        });

    });


});

</script>

@stop