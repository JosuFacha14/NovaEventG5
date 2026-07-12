@extends('adminlte::page')

@section('title', 'Perfil de Persona')

@section('content_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h3 class="mb-0">
                <i class="bi bi-person-badge me-1"></i>
                Perfil de Persona
            </h3>
        </div>
        <div class="col-sm-6 text-end">
            <a href="{{ route('personas.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
    </div>
@stop

@section('content')

    {{-- ── Datos principales ── --}}
    <x-adminlte-card icon="bi bi-person-circle" theme="primary" collapsible>
        <x-slot name="title">
            {{ $persona['PRIMER_NOMBRE'] }}
            {{ $persona['SEGUNDO_NOMBRE'] ?? '' }}
            {{ $persona['APELLIDO'] }}
        </x-slot>

        <div class="row g-3">
            <div class="col-md-3 col-6">
                <p class="mb-1 text-muted small fw-semibold">DNI</p>
                <p class="mb-0">{{ $persona['DNI'] }}</p>
            </div>
            <div class="col-md-3 col-6">
                <p class="mb-1 text-muted small fw-semibold">Sexo</p>
                <p class="mb-0">
                    @switch($persona['SEXO'])
                        @case('M') Masculino @break
                        @case('F') Femenino  @break
                        @case('O') Otro      @break
                        @default   No Dice
                    @endswitch
                </p>
            </div>
            <div class="col-md-3 col-6">
                <p class="mb-1 text-muted small fw-semibold">Estado Civil</p>
                <p class="mb-0">
                    @switch($persona['EST_CIVIL'])
                        @case('S') Soltero/a @break
                        @case('C') Casado/a  @break
                        @case('V') Viudo/a   @break
                        @default   —
                    @endswitch
                </p>
            </div>
            <div class="col-md-3 col-6">
                <p class="mb-1 text-muted small fw-semibold">Edad</p>
                <p class="mb-0">{{ $persona['EDAD'] }} años</p>
            </div>
            <div class="col-md-3 col-6">
                <p class="mb-1 text-muted small fw-semibold">Tipo Persona</p>
                <p class="mb-0">{{ $persona['TIP_PERSONA'] === 'N' ? 'Natural' : 'Jurídica' }}</p>
            </div>
            <div class="col-md-3 col-6">
                <p class="mb-1 text-muted small fw-semibold">Registrado por</p>
                <p class="mb-0">{{ $persona['USR_INGRESO'] }}</p>
            </div>
            <div class="col-md-3 col-6">
                <p class="mb-1 text-muted small fw-semibold">Fecha registro</p>
                <p class="mb-0">{{ $persona['FEC_INGRESO'] }}</p>
            </div>
        </div>
    </x-adminlte-card>

    <div class="row g-3">

        {{-- ── Teléfonos ── --}}
        <div class="col-md-6">
            <x-adminlte-card title="Teléfonos" icon="bi bi-telephone" theme="info" collapsible>
                @if(count($telefonos) > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($telefonos as $tel)
                            @php
                                // El GET de personas-telefonos devuelve la relación;
                                // los datos del teléfono están aplanados si el SP hace JOIN,
                                // o sólo los códigos. Mostramos lo que llega.
                            @endphp
                            <li class="list-group-item d-flex justify-content-between">
                                <span>
                                    <i class="bi bi-phone me-1"></i>
                                    {{ $tel['NUM_AREA'] ?? '' }}-{{ $tel['NUM_TELEFONO'] ?? $tel['COD_TELEFONO'] }}
                                </span>
                                <span class="badge bg-secondary">
                                    @switch($tel['TIP_TELEFONO'] ?? '')
                                        @case('C') Celular  @break
                                        @case('O') Oficina  @break
                                        @case('P') Personal @break
                                        @default   —
                                    @endswitch
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">Sin teléfonos registrados.</p>
                @endif
            </x-adminlte-card>
        </div>

        {{-- ── Correos ── --}}
        <div class="col-md-6">
            <x-adminlte-card title="Correos" icon="bi bi-envelope" theme="info" collapsible>
                @if(count($correos) > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($correos as $cor)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>
                                    <i class="bi bi-at me-1"></i>
                                    {{ ($cor['USUARIO_CORREO'] ?? '') . '@' . ($cor['SERVIDOR_CORREO'] ?? '') }}
                                </span>
                                <span class="badge bg-secondary">
                                    {{ ($cor['TIP_CORREO'] ?? '') === 'P' ? 'Personal' : 'Oficina' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">Sin correos registrados.</p>
                @endif
            </x-adminlte-card>
        </div>

        {{-- ── Usuario ── --}}
        @if(count($usuarios) > 0)
        <div class="col-md-6">
            <x-adminlte-card title="Cuenta de Usuario" icon="bi bi-shield-lock" theme="secondary" collapsible>
                @php $usr = $usuarios[0]; @endphp
                <dl class="row mb-0">
                    <dt class="col-sm-4">Usuario</dt>
                    <dd class="col-sm-8">{{ $usr['NOMBRE'] }}</dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        @if(($usr['IND_USR'] ?? '0') === '1')
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </dd>
                    <dt class="col-sm-4">Primer ingreso</dt>
                    <dd class="col-sm-8">{{ ($usr['IND_PRIMER_ING'] ?? '0') === '1' ? 'Pendiente' : 'Completado' }}</dd>
                </dl>
            </x-adminlte-card>
        </div>
        @endif

        {{-- ── Cliente ── --}}
        @if(count($clientes) > 0)
        <div class="col-md-6">
            <x-adminlte-card title="Datos de Cliente" icon="bi bi-briefcase" theme="success" collapsible>
                @php $cli = $clientes[0]; @endphp
                <dl class="row mb-0">
                    <dt class="col-sm-4">Empresa</dt>
                    <dd class="col-sm-8">{{ $cli['NOM_EMPRESA'] ?? '—' }}</dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ ($cli['IND_CLIENTE'] ?? '0') === '1' ? 'success' : 'danger' }}">
                            {{ ($cli['IND_CLIENTE'] ?? '0') === '1' ? 'Activo' : 'Inactivo' }}
                        </span>
                    </dd>
                </dl>
            </x-adminlte-card>
        </div>
        @endif

        {{-- ── Empleado ── --}}
        @if(count($empleados) > 0)
        <div class="col-md-6">
            <x-adminlte-card title="Datos de Empleado" icon="bi bi-person-workspace" theme="warning" collapsible>
                @php $emp = $empleados[0]; @endphp
                <dl class="row mb-0">
                    <dt class="col-sm-4">Cargo</dt>
                    <dd class="col-sm-8">{{ $emp['CARGO'] }}</dd>
                    <dt class="col-sm-4">Contratación</dt>
                    <dd class="col-sm-8">{{ $emp['FEC_CONTRATACION'] }}</dd>
                    <dt class="col-sm-4">Salario</dt>
                    <dd class="col-sm-8">L. {{ number_format($emp['SALARIO'], 2) }}</dd>
                </dl>
            </x-adminlte-card>
        </div>
        @endif

        {{-- ── Proveedor ── --}}
        @if(count($proveedores) > 0)
        <div class="col-md-6">
            <x-adminlte-card title="Datos de Proveedor" icon="bi bi-truck" theme="danger" collapsible>
                @php $prov = $proveedores[0]; @endphp
                <dl class="row mb-0">
                    <dt class="col-sm-4">Empresa</dt>
                    <dd class="col-sm-8">{{ $prov['EMPRESA'] }}</dd>
                    <dt class="col-sm-4">Categoría</dt>
                    <dd class="col-sm-8">{{ $prov['CATEGORIA_SERVICIO'] ?? '—' }}</dd>
                </dl>
            </x-adminlte-card>
        </div>
        @endif

    </div>

@stop