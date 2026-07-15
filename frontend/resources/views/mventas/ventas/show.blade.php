@extends('adminlte::page')

@section('title','Detalle de Venta')

@section('content_header')

<h1>Detalle de Venta #{{ $id }}</h1>

@stop

@section('content')

<div class="card">

    <div class="card-header bg-info">

        <h3 class="card-title">

            Productos de la venta

        </h3>

    </div>

    <div class="card-body">

        @if(empty($detalle))

            <div class="alert alert-warning">

                No existen detalles para esta venta.

            </div>

        @else

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>Boleto</th>

                        <th>Cantidad</th>

                        <th>Precio Unitario</th>

                        <th>Subtotal</th>

                    </tr>

                </thead>

                <tbody>

                @foreach($detalle as $item)

                    <tr>

                        <td>{{ $item['COD_BOLETO'] }}</td>

                        <td>{{ $item['NUM_CANTIDAD'] }}</td>

                        <td>

                            L.
                            {{ number_format($item['MON_PRECIO_UNIT'],2) }}

                        </td>

                        <td>

                            L.
                            {{ number_format($item['MON_SUBTOTAL'],2) }}

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        @endif

    </div>

    <div class="card-footer">

        <a
            href="{{ route('ventas.index') }}"
            class="btn btn-secondary">

            Regresar

        </a>

    </div>

</div>

@stop