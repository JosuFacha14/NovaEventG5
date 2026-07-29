<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NovaEvent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="card login-card border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-0">NOVA<span style="color:#c9a84c;">EVENT</span></h4>
                <p class="text-muted small">Iniciar Sesión</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success small p-2 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger small p-2 text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre_usr" class="form-label small fw-bold">Usuario</label>
                    <input type="text" class="form-control" id="nombre_usr" name="nombre_usr" value="{{ old('nombre_usr') }}" required autofocus>
                    @error('nombre_usr')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="clave" class="form-label small fw-bold">Contraseña</label>
                    <input type="password" class="form-control" id="clave" name="clave" required>
                    @error('clave')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2" style="background-color: #0d1b2a; border-color: #0d1b2a;">Ingresar</button>
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-decoration-none small" style="color: #c9a84c; font-weight: 500;">¿No tienes cuenta? Regístrate</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
