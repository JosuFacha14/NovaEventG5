@extends('adminlte::page')

@section('title', 'Categorías de Evento')

@section('content_header')
    <h1>Catálogo de Categorías de Evento</h1>
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
        <h3 class="card-title">Categorías Registradas</h3>

        <div class="card-tools">
            <button type="button"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCrear">
                <i class="bi bi-plus-lg"></i>
                Nueva Categoría
            </button>
        </div>
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

            @forelse($categorias as $categoria)

                <tr>

                    <td>{{ $categoria['COD_CATEGORIA'] }}</td>

                    <td>{{ $categoria['NOM_CATEGORIA'] }}</td>

                    <td>{{ $categoria['DES_CATEGORIA'] }}</td>

                    <td>
                        @if($categoria['IND_ACTIVO'] == '1')
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
                                class="btn btn-info btn-sm btnVer"
                                title="Ver categoría"
                                data-bs-toggle="modal"
                                data-bs-target="#modalVer"
                                data-id="{{ $categoria['COD_CATEGORIA'] }}"
                                data-nombre="{{ $categoria['NOM_CATEGORIA'] }}"
                                data-descripcion="{{ $categoria['DES_CATEGORIA'] }}"
                                data-estado="{{ $categoria['IND_ACTIVO'] }}">

                            <i class="bi bi-eye"></i>

                        </button>

                        {{-- EDITAR --}}
                        <button type="button"
                                class="btn btn-warning btn-sm btnEditar"
                                title="Editar categoría"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditar"
                                data-id="{{ $categoria['COD_CATEGORIA'] }}"
                                data-nombre="{{ $categoria['NOM_CATEGORIA'] }}"
                                data-descripcion="{{ $categoria['DES_CATEGORIA'] }}">

                            <i class="bi bi-pencil"></i>

                        </button>

                        {{-- BAJA LÓGICA --}}
                        <button type="button"
                                class="btn btn-danger btn-sm btnBaja"
                                title="Dar de baja"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBaja"
                                data-id="{{ $categoria['COD_CATEGORIA'] }}"
                                data-nombre="{{ $categoria['NOM_CATEGORIA'] }}">

                            <i class="bi bi-person-dash"></i>

                        </button>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No existen categorías registradas.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- MODAL CREAR --}}
@include('mventas.categorias-evento._create')


{{-- MODAL EDITAR --}}
@include('mventas.categorias-evento._edit')


{{-- MODAL VER --}}
<div class="modal fade"
     id="modalVer"
     tabindex="-1"
     aria-labelledby="modalVerLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="modalVerLabel">
                    <i class="bi bi-eye"></i>
                    Detalle de Categoría
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Código</label>

                    <input type="text"
                           id="ver_id"
                           class="form-control"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>

                    <input type="text"
                           id="ver_nombre"
                           class="form-control"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>

                    <textarea id="ver_descripcion"
                              class="form-control"
                              rows="4"
                              readonly></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>

                    <input type="text"
                           id="ver_estado"
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


{{-- MODAL CONFIRMAR BAJA --}}
<div class="modal fade"
     id="modalBaja"
     tabindex="-1"
     aria-labelledby="modalBajaLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title" id="modalBajaLabel">
                    <i class="bi bi-exclamation-triangle"></i>
                    Confirmar baja
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>

            <form id="formBaja" method="POST">

                @csrf
                @method('PUT')

                <div class="modal-body">

                    <p>
                        ¿Está seguro de que desea dar de baja la categoría
                        <strong id="baja_nombre"></strong>?
                    </p>

                    <small class="text-muted">
                        Esta acción desactivará la categoría.
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
    | VER CATEGORÍA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnVer').forEach(function (boton) {

        boton.addEventListener('click', function () {

            document.getElementById('ver_id').value =
                this.getAttribute('data-id');

            document.getElementById('ver_nombre').value =
                this.getAttribute('data-nombre');

            document.getElementById('ver_descripcion').value =
                this.getAttribute('data-descripcion');

            document.getElementById('ver_estado').value =
                this.getAttribute('data-estado') === '1'
                    ? 'Activo'
                    : 'Inactivo';

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EDITAR CATEGORÍA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEditar').forEach(function (boton) {

        boton.addEventListener('click', function () {

            const id = this.getAttribute('data-id');

            document.getElementById('edit_id').value = id;

            document.getElementById('edit_nom_categoria').value =
                this.getAttribute('data-nombre');

            document.getElementById('edit_des_categoria').value =
                this.getAttribute('data-descripcion');

            let url =
                "{{ route('categorias-evento.update', ['id' => '__ID__']) }}";

            url = url.replace('__ID__', id);

            document.getElementById('formEditar').action = url;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR BAJA LÓGICA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnBaja').forEach(function (boton) {

        boton.addEventListener('click', function () {

            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');

            document.getElementById('baja_nombre').textContent = nombre;

            let url =
                "{{ route('categorias-evento.destroy', ['id' => '__ID__']) }}";

            url = url.replace('__ID__', id);

            document.getElementById('formBaja').action = url;

        });

    });

});

</script>

@stop