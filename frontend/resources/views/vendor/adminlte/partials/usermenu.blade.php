@php
    $user = session('usuario');
    // Priorizar NOMBRE_USR que es lo que devuelve el SP de login
    $name = 'Usuario';
    if (!empty($user['NOMBRE_USR'])) {
        $name = $user['NOMBRE_USR'];
    } elseif (!empty($user['nombre_usr'])) {
        $name = $user['nombre_usr'];
    } elseif (!empty($user['nombreUsr'])) {
        $name = $user['nombreUsr'];
    } elseif ((!empty($user['NOMBRES']) || !empty($user['APELLIDOS']))) {
        $name = trim(($user['NOMBRES'] ?? '') . ' ' . ($user['APELLIDOS'] ?? ''));
    }

    $role = $user['NOM_TIPO'] ?? $user['nom_tipo'] ?? 'Usuario';
    
    $avatar = asset('vendor/adminlte/img/user2-160x160.jpg');
@endphp
<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <span class="d-none d-md-inline">{{ $name }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        {{-- Header --}}
        <li class="user-header text-bg-primary" style="background-color: #0d1b2a !important;">
            <img src="{{ $avatar }}" class="rounded-circle shadow" alt="{{ $name }}" width="90" height="90">
            <p>
                {{ $name }}
                <small>{{ $role }}</small>
            </p>
        </li>
        {{-- Footer --}}
        <li class="user-footer text-center">
            <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
            </a>
        </li>
    </ul>
</li>
