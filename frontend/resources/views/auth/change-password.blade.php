<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - NovaEvent</title>
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
                <p class="text-muted small">Cambio de Contraseña</p>
            </div>

            @if(session('requiere_cambio_clave'))
                <div class="alert alert-warning small p-2 text-center">
                    Por motivos de seguridad, es obligatorio cambiar su contraseña en su primer ingreso.
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger small p-2 text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('password.change.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="clave" class="form-label small fw-bold">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="clave" name="clave" required autofocus>
                    @error('clave')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="clave_confirmation" class="form-label small fw-bold">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="clave_confirmation" name="clave_confirmation" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100" style="background-color: #0d1b2a; border-color: #0d1b2a;">Actualizar Contraseña</button>
            </form>
        </div>
    </div>
</body>
</html>
