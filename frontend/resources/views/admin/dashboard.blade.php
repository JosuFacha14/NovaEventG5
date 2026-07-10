@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
    </div>
@stop

@section('content')
    <div class="row g-3">
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box title="150" text="New Orders"
                icon="bi bi-cart" theme="primary" url="#" />
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="44" text="Registrations"
                icon="bi bi-person-plus" theme="success" />
        </div>
    </div>

    <x-adminlte-card title="Quick form" icon="bi bi-pencil"
        theme="primary" outline collapsible>
        <x-adminlte-input name="email" label="Email" type="email"
            placeholder="you@example.com" />
        <x-adminlte-button type="submit" theme="primary"
            icon="bi bi-check-lg" label="Save" />
    </x-adminlte-card>
@stop