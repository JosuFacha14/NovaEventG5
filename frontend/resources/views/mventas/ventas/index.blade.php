@extends('adminlte::page')

@section('title','Gestión de Ventas')

@section('content_header')

<div class="row align-items-center">

    <div class="col-sm-6">
        <h3 class="mb-0">
            Gestión de Ventas
        </h3>
    </div>

    <div class="col-sm-6 text-end">

        <button
            class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalCrear">

            <i class="bi bi-plus-lg me-1"></i>
            Nueva Venta

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

@if($errors->any())
<div class="alert alert-danger">
    <strong>Se encontraron errores:</strong>

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
    <i class="bi bi-cart-check-fill me-2"></i>
    Ventas Registradas
</h3>

</div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Método Pago</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($ventas as $venta)

                    <tr>

                        <td>{{ $venta['COD_VENTA'] }}</td>

                        <td>{{ $venta['COD_CLIENTE'] }}</td>

                        <td>
                            L. {{ number_format($venta['MON_TOTAL'],2) }}
                        </td>

                        <td>
                            {{ $venta['IND_METODO_PAGO'] }}
                        </td>

                        <td>

                            @if($venta['IND_ESTADO_VENTA']=='PAGADA')

                                <span class="badge bg-success">
                                    PAGADA
                                </span>

                            @elseif($venta['IND_ESTADO_VENTA']=='PENDIENTE')

                                <span class="badge bg-warning">
                                    PENDIENTE
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    CANCELADA
                                </span>

                            @endif

                        </td>

                        <td class="text-center">

                            {{-- VER --}}
                            <button
                                class="btn btn-info btn-sm btnVer"
                                data-bs-toggle="modal"
                                data-bs-target="#modalVer"

                                data-id="{{ $venta['COD_VENTA'] }}"
                                data-cliente="{{ $venta['COD_CLIENTE'] }}"
                                data-total="{{ $venta['MON_TOTAL'] }}"
                                data-metodo="{{ $venta['IND_METODO_PAGO'] }}"
                                data-estado="{{ $venta['IND_ESTADO_VENTA'] }}">

                                <i class="bi bi-eye"></i>

                            </button>


                            {{-- EDITAR --}}
                            <button
                                class="btn btn-warning btn-sm btnEditar"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditar"

                                data-id="{{ $venta['COD_VENTA'] }}"
                                data-cliente="{{ $venta['COD_CLIENTE'] }}"
                                data-total="{{ $venta['MON_TOTAL'] }}"
                                data-metodo="{{ $venta['IND_METODO_PAGO'] }}"
                                data-estado="{{ $venta['IND_ESTADO_VENTA'] }}">

                                <i class="bi bi-pencil"></i>

                            </button>


                            {{-- DAR DE BAJA --}}
                            <button
                                class="btn btn-danger btn-sm btnEliminar"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminar"

                                data-id="{{ $venta['COD_VENTA'] }}"
                                data-cliente="{{ $venta['COD_CLIENTE'] }}">

                                <i class="bi bi-person-dash"></i>

                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            No existen ventas registradas.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@include('mventas.ventas._create')
@include('mventas.ventas._edit')
@include('mventas.ventas._view')


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

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    ¿Está seguro de que desea dar de baja la venta
                    <strong id="ventaEliminar"></strong>?
                </p>

                <small class="text-muted">
                    Esta acción desactivará la venta.
                    No se eliminarán los datos.
                </small>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancelar

                </button>

                <form
                    id="formEliminar"
                    action=""
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
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
    | VER VENTA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnVer').forEach(function(boton){

        boton.addEventListener('click', function(){

            document.getElementById('ver_cod_venta').value =
                this.dataset.id;

            document.getElementById('ver_cod_cliente').value =
                this.dataset.cliente;

            document.getElementById('ver_mon_total').value =
                this.dataset.total;

            document.getElementById('ver_metodo_pago').value =
                this.dataset.metodo;

            document.getElementById('ver_estado').value =
                this.dataset.estado;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | EDITAR VENTA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEditar').forEach(function(boton){

        boton.addEventListener('click', function(){

            let id = this.dataset.id;

            document.getElementById('edit_mon_total').value =
                this.dataset.total;

            document.getElementById('edit_metodo_pago').value =
                this.dataset.metodo;

            document.getElementById('edit_estado_venta').value =
                this.dataset.estado;

            let url =
                "{{ route('ventas.update',['id'=>'__ID__']) }}";

            url =
                url.replace('__ID__', id);

            document.getElementById('formEditar').action =
                url;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR BAJA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEliminar').forEach(function(boton){

        boton.addEventListener('click', function(){

            const id =
                this.dataset.id;

            document.getElementById('ventaEliminar').textContent =
                id;

            let url =
                "{{ route('ventas.destroy',['id'=>'__ID__']) }}";

            url =
                url.replace('__ID__', id);

            document.getElementById('formEliminar').action =
                url;

        });

    });

});

</script>

@stop