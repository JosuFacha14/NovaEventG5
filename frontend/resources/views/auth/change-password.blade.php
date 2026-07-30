<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña - NovaEvent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #e8dcc8;
            background-image:
                radial-gradient(ellipse at 20% 30%, rgba(255,255,255,0.55) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 70%, rgba(255,255,255,0.45) 0%, transparent 50%),
                radial-gradient(ellipse at 60% 15%, rgba(255,255,255,0.35) 0%, transparent 40%);
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13), 0 2px 8px rgba(0,0,0,0.07);
            padding: 2.2rem 2rem 1.8rem;
            width: 340px;
            max-width: 100%;
            border: none;
        }
        .logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 0.3rem;
        }
        .logo-icon { width: 52px; height: 52px; flex-shrink: 0; object-fit: contain; }
        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3142;
            letter-spacing: 0.04em;
            line-height: 1;
        }
        .logo-text span { color: #c9a84c; }
        .card-subtitle {
            text-align: center;
            font-size: 0.82rem;
            color: #9a9a9a;
            margin: 0 0 1.6rem;
        }
        .field-group { margin-bottom: 1.1rem; }
        .field-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #4a4a5a;
            margin-bottom: 0.35rem;
        }
        .input-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #d8d8e0;
            border-radius: 8px;
            padding: 0 10px;
            height: 40px;
            background: #fff;
            transition: border-color 0.15s;
        }
        .input-wrap:focus-within { border-color: #9ba4bc; }
        .input-wrap i { font-size: 16px; color: #b0b0bf; margin-right: 8px; flex-shrink: 0; }
        .input-wrap input {
            border: none;
            outline: none;
            font-size: 0.85rem;
            color: #2d3142;
            flex: 1;
            background: transparent;
            font-family: inherit;
        }
        .input-wrap input::placeholder { color: #c8c8d4; }
        .input-wrap.is-invalid { border-color: #dc3545; }
        .btn-login {
            display: block;
            width: 80%;
            margin: 1.5rem auto 0;
            padding: 0.62rem 0;
            background: #0d1b2a;
            color: #fff;
            border: none;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: background 0.15s, transform 0.1s;
            text-align: center;
        }
        .btn-login:hover { background: #1a2e45; }
        .btn-login:active { transform: scale(0.98); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-row">
            <img src="{{ asset('images/novaeventLogo.png') }}" class="logo-icon" alt="NovaEvent Logo">
            <div class="logo-text">NOVA<span>EVENT</span></div>
        </div>
        <p class="card-subtitle">Cambio de Contraseña</p>

        @if(session('requiere_cambio_clave'))
            <div class="alert alert-warning small p-2 text-center mb-3">
                Por motivos de seguridad, es obligatorio cambiar su contraseña en su primer ingreso.
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger small p-2 text-center mb-3">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('password.change.post') }}" method="POST">
            @csrf

            <div class="field-group">
                <label for="clave">Nueva Contraseña</label>
                <div class="input-wrap {{ $errors->has('clave') ? 'is-invalid' : '' }}">
                    <i class="ti ti-lock" aria-hidden="true"></i>
                    <input type="password"
                           id="clave"
                           name="clave"
                           placeholder="Nueva contraseña"
                           required
                           autofocus>
                </div>
                @error('clave')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="field-group">
                <label for="clave_confirmation">Confirmar Contraseña</label>
                <div class="input-wrap">
                    <i class="ti ti-lock-check" aria-hidden="true"></i>
                    <input type="password"
                           id="clave_confirmation"
                           name="clave_confirmation"
                           placeholder="Confirme su contraseña"
                           required>
                </div>
            </div>

            <button type="submit" class="btn-login">Actualizar Contraseña</button>
        </form>
    </div>
</body>
</html>