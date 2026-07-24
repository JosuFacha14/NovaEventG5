@extends('adminlte::page')

@section('title', 'Panel de Reportes')

@section('content_header')
    <h1 class="m-0 text-dark">Panel General de Reportes</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $datos['total_eventos'] ?? 0 }}</h3>
                    <p>Eventos Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('reportes.ganancias') }}" class="small-box-footer">
                    Ver Ganancias <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>L. {{ number_format($datos['total_ganancias'] ?? 0, 2) }}</h3>
                    <p>Ingresos Totales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('reportes.ganancias') }}" class="small-box-footer">
                    Ver Ganancias <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>L. {{ number_format($datos['total_costos'] ?? 0, 2) }}</h3>
                    <p>Costos Operativos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('reportes.costos') }}" class="small-box-footer">
                    Ver Costos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
