@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="row align-items-center">
    <div class="col-sm-6">
        <h3 class="mb-0">Inicio</h3>
    </div>
</div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 5px solid #198754;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{--HERO: Logo + Título en banner fino horizontañ --}}

<div class="card border-0 shadow-sm mb-4 overflowhidden"
     style="background: linear-gradient(120deg, #0d1b2a 0%, #1a2f4a 60%, #162840 100%);">
    <div class="card-body py-3 px-4 d-flex align-items-center gap-4">
        <img src="{{ asset('images/novaeventLogo.png') }}"
             alt="NovaEvent Logo"
             class="rounded shadow-sm flex-shrink-0"
             style="width: 64px; height: 64px; object-fit: cover;">
        <div>
            <h4 class="fw-bold mb-0" style="color:#fff; letter-spacing: 2px;">
                NOVA<span style="color:#c9a84c;">EVENT</span>
            </h4>
            <p class="mb-0" style="color:#a0b4c8; font-size:0.7rem; letter-spacing: 3px;">
                SISTEMA INTEGRAL PARA EVENTOS
            </p>
        </div>
        
    </div>
</div>

{{--OBJETIVO GENERAL --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="p-3 rounded-3 d-flex align-items-start gap-3"
             style="background: var(--bs-tertiary-bg); border-left: 5px solid #c9a84c;">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px; height:40px; background:#c9a84c25;">
                <i class="bi bi-bullseye" style="color:#c9a84c; font-size:1.1rem;"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1" style="color:#c9a84c;">Objetivo General</h6>
                <p class="mb-0 text-secondary small lh-lg">
                    Desarrollar un sistema de información para la gestión de eventos que centralice y automatice
                    los procesos de administración de personas, reservación de espacios, control de inventario,
                    ventas y generación de reportes, con el fin de optimizar el ciclo operativo y financiero de
                    cada evento, desde la cotización inicial hasta su cierre logístico y contable.
                </p>
            </div>
        </div>
    </div>
</div>

{{--OBJETIVOS ESPECÍFICOS — tarjetas estilo dashboard --}}
<div class="mb-1">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-list-check" style="color:#1a6fc4; font-size:1.1rem;"></i>
        <h6 class="fw-semibold mb-0">Objetivos Específicos</h6>
    </div>
    <div class="row g-3">

        {{-- Administrar --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="p-3 rounded-3 h-100 text-center"
                 style="background: var(--bs-tertiary-bg); border-top: 4px solid #1a6fc4;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                     style="width:46px; height:46px; background:#1a6fc420;">
                    <i class="bi bi-people-fill" style="color:#1a6fc4; font-size:1.2rem;"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color:#1a6fc4; font-size:0.85rem;">Administrar</h6>
                <p class="mb-0 text-secondary" style="font-size:0.75rem; line-height:1.4;">
                    La información de los eventos de manera organizada y eficiente.
                </p>
            </div>
        </div>

        {{-- Estandarizar --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="p-3 rounded-3 h-100 text-center"
                 style="background: var(--bs-tertiary-bg); border-top: 4px solid #6f42c1;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                     style="width:46px; height:46px; background:#6f42c120;">
                    <i class="bi bi-calendar-check-fill" style="color:#6f42c1; font-size:1.2rem;"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color:#6f42c1; font-size:0.85rem;">Estandarizar</h6>
                <p class="mb-0 text-secondary" style="font-size:0.75rem; line-height:1.4;">
                    Los procesos de administración mediante flujos estandarizados.
                </p>
            </div>
        </div>

        {{-- Desarrollar --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="p-3 rounded-3 h-100 text-center"
                 style="background: var(--bs-tertiary-bg); border-top: 4px solid #fd7e14;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                     style="width:46px; height:46px; background:#fd7e1420;">
                    <i class="bi bi-cart-fill" style="color:#fd7e14; font-size:1.2rem;"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color:#fd7e14; font-size:0.85rem;">Desarrollar</h6>
                <p class="mb-0 text-secondary" style="font-size:0.75rem; line-height:1.4;">
                    Un sistema de supervisión de reservaciones y ventas en tiempo real.
                </p>
            </div>
        </div>

        {{-- Optimizar --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="p-3 rounded-3 h-100 text-center"
                 style="background: var(--bs-tertiary-bg); border-top: 4px solid #198754;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                     style="width:46px; height:46px; background:#19875420;">
                    <i class="bi bi-boxes" style="color:#198754; font-size:1.2rem;"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color:#198754; font-size:0.85rem;">Optimizar</h6>
                <p class="mb-0 text-secondary" style="font-size:0.75rem; line-height:1.4;">
                    La gestión de recursos e inventario mediante control automatizado.
                </p>
            </div>
        </div>

        {{-- Construir --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="p-3 rounded-3 h-100 text-center"
                 style="background: var(--bs-tertiary-bg); border-top: 4px solid #dc3545;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                     style="width:46px; height:46px; background:#dc354520;">
                    <i class="bi bi-file-earmark-bar-graph-fill" style="color:#dc3545; font-size:1.2rem;"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color:#dc3545; font-size:0.85rem;">Construir</h6>
                <p class="mb-0 text-secondary" style="font-size:0.75rem; line-height:1.4;">
                    Los módulos operativos con base de datos relacional.
                </p>
            </div>
        </div>

    </div>
</div>

{{-- MODULOS DEL SISTEMA — tarjetas navegables  --}}
<div class="mt-4 mb-2">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-grid-fill" style="color:#0d1b2a; font-size:1.1rem;"></i>
        <h6 class="fw-semibold mb-0">Módulos del Sistema</h6>
    </div>
    <div class="row g-3">

        <div class="col-6 col-md-4 col-lg">
            <a href="{{ url('personas') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 text-center h-100 dashboard-mod-card"
                     style="background: var(--bs-tertiary-bg); border-top: 4px solid #1a6fc4; transition: transform .15s, box-shadow .15s;">
                    <i class="bi bi-people-fill mb-2 d-block" style="font-size:1.8rem; color:#1a6fc4;"></i>
                    <span class="fw-semibold small" style="color:#1a6fc4;">Personas</span>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <a href="{{ url('espacios') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 text-center h-100 dashboard-mod-card"
                     style="background: var(--bs-tertiary-bg); border-top: 4px solid #6f42c1; transition: transform .15s, box-shadow .15s;">
                    <i class="bi bi-calendar-check-fill mb-2 d-block" style="font-size:1.8rem; color:#6f42c1;"></i>
                    <span class="fw-semibold small" style="color:#6f42c1;">Reservaciones</span>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <a href="{{ url('ventas') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 text-center h-100 dashboard-mod-card"
                     style="background: var(--bs-tertiary-bg); border-top: 4px solid #fd7e14; transition: transform .15s, box-shadow .15s;">
                    <i class="bi bi-cart-fill mb-2 d-block" style="font-size:1.8rem; color:#fd7e14;"></i>
                    <span class="fw-semibold small" style="color:#fd7e14;">Ventas</span>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <a href="{{ url('inventario/items') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 text-center h-100 dashboard-mod-card"
                     style="background: var(--bs-tertiary-bg); border-top: 4px solid #198754; transition: transform .15s, box-shadow .15s;">
                    <i class="bi bi-boxes mb-2 d-block" style="font-size:1.8rem; color:#198754;"></i>
                    <span class="fw-semibold small" style="color:#198754;">Inventario</span>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg">
            <a href="{{ url('mreportes/ganancias') }}" class="text-decoration-none">
                <div class="p-3 rounded-3 text-center h-100 dashboard-mod-card"
                     style="background: var(--bs-tertiary-bg); border-top: 4px solid #dc3545; transition: transform .15s, box-shadow .15s;">
                    <i class="bi bi-file-earmark-bar-graph-fill mb-2 d-block" style="font-size:1.8rem; color:#dc3545;"></i>
                    <span class="fw-semibold small" style="color:#dc3545;">Reportes</span>
                </div>
            </a>
        </div>

    </div>
</div>

@stop

@section('css')
<style>
    .card { border-radius: 12px !important; }
    .dashboard-mod-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,.12) !important;
    }
</style>
@stop