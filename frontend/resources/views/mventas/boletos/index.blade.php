@extends('adminlte::page')

@section('title', 'Gestión de Boletos')

@section('content_header')
    <h1>Gestión de Boletos</h1>
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


@php
    $listaEventos = [];

    foreach($eventos as $evento){
        $listaEventos[$evento['COD_EVENTO']] = $evento['NOM_EVENTO'];
    }
@endphp


<div class="card card-primary">

    <div class="card-header">

        <h3 class="card-title">
            Boletos Registrados
        </h3>

        <div class="card-tools">

            <button type="button"
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCrear">

                <i class="bi bi-plus-lg"></i>
                Nuevo Boleto

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
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Disponibles</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>

                </tr>

                </thead>

                <tbody>

                @forelse($boletos as $boleto)

                    <tr>

                        <td>{{ $boleto['COD_BOLETO'] }}</td>

                        <td>
                            {{ $listaEventos[$boleto['COD_EVENTO']] ?? $boleto['COD_EVENTO'] }}
                        </td>

                        <td>{{ $boleto['TIP_BOLETO'] }}</td>

                        <td>L. {{ number_format($boleto['MON_PRECIO'],2) }}</td>

                        <td>{{ $boleto['NUM_DISPONIBLE'] }}</td>

                        <td>

                            @if($boleto['IND_ACTIVO']=='1')

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
                            <button
                                class="btn btn-info btn-sm btnVer"

                                data-bs-toggle="modal"
                                data-bs-target="#modalVer"

                                data-id="{{ $boleto['COD_BOLETO'] }}"
                                data-evento="{{ $listaEventos[$boleto['COD_EVENTO']] ?? $boleto['COD_EVENTO'] }}"
                                data-tipo="{{ $boleto['TIP_BOLETO'] }}"
                                data-precio="{{ $boleto['MON_PRECIO'] }}"
                                data-disponible="{{ $boleto['NUM_DISPONIBLE'] }}"
                                data-descripcion="{{ $boleto['DES_BOLETO'] }}"
                                data-estado="{{ $boleto['IND_ACTIVO'] }}">

                                <i class="bi bi-eye"></i>

                            </button>


                            {{-- EDITAR --}}
                            <button
                                class="btn btn-warning btn-sm btnEditar"

                                data-bs-toggle="modal"
                                data-bs-target="#modalEditar"

                                data-id="{{ $boleto['COD_BOLETO'] }}"
                                data-evento="{{ $listaEventos[$boleto['COD_EVENTO']] ?? $boleto['COD_EVENTO'] }}"
                                data-tipo="{{ $boleto['TIP_BOLETO'] }}"
                                data-precio="{{ $boleto['MON_PRECIO'] }}"
                                data-disponible="{{ $boleto['NUM_DISPONIBLE'] }}"
                                data-descripcion="{{ $boleto['DES_BOLETO'] }}">

                                <i class="bi bi-pencil"></i>

                            </button>


                            {{-- DAR DE BAJA --}}


    {{-- DAR DE BAJA --}}

{{-- BAJA LÓGICA --}}
<button type="button"
        class="btn btn-danger btn-sm btnEliminar"
        title="Dar de baja"
        data-bs-toggle="modal"
        data-bs-target="#modalEliminar"
        data-id="{{ $boleto['COD_BOLETO'] }}"
        data-evento="{{ $listaEventos[$boleto['COD_EVENTO']] ?? $boleto['COD_EVENTO'] }}">

    <i class="bi bi-person-dash"></i>

</button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            No existen boletos registrados.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- MODAL CREAR --}}
@include('mventas.boletos._create')

{{-- MODAL EDITAR --}}
@include('mventas.boletos._edit')

{{-- MODAL VER --}}
@include('mventas.boletos._view')

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
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    ¿Está seguro de que desea dar de baja el boleto del evento
                    <strong id="nombreBoletoEliminar"></strong>?
                </p>

                <small class="text-muted">
                    Esta acción desactivará el boleto.
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
    | VER BOLETO
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnVer').forEach(function(boton){

        boton.addEventListener('click', function(){

            document.getElementById('ver_cod_boleto').value = this.dataset.id;
            document.getElementById('ver_nom_evento').value = this.dataset.evento;
            document.getElementById('ver_tip_boleto').value = this.dataset.tipo;
            document.getElementById('ver_mon_precio').value = this.dataset.precio;
            document.getElementById('ver_num_disponible').value = this.dataset.disponible;
            document.getElementById('ver_des_boleto').value = this.dataset.descripcion;

            document.getElementById('ver_estado').value =
                this.dataset.estado == '1'
                ? 'Activo'
                : 'Inactivo';

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EDITAR BOLETO
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEditar').forEach(function(boton){

        boton.addEventListener('click', function(){

            let id = this.dataset.id;

            document.getElementById('edit_cod_boleto').value = id;
            document.getElementById('edit_cod_evento').value = this.dataset.evento;
            document.getElementById('edit_tip_boleto').value = this.dataset.tipo;
            document.getElementById('edit_mon_precio').value = this.dataset.precio;
            document.getElementById('edit_num_disponible').value = this.dataset.disponible;
            document.getElementById('edit_des_boleto').value = this.dataset.descripcion;

            let url = "{{ route('boletos.update',['id'=>'__ID__']) }}";
            url = url.replace('__ID__', id);

            document.getElementById('formEditar').action = url;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR BAJA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEliminar').forEach(function (boton) {

    boton.addEventListener('click', function () {

        const id =
            this.getAttribute('data-id');

        const evento =
            this.getAttribute('data-evento');

        document.getElementById(
            'nombreBoletoEliminar'
        ).textContent = evento;

        let url =
            "{{ route('boletos.destroy', ['id' => '__ID__']) }}";

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