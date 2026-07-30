<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - NovaEvent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 2rem 0;
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
        .register-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.13), 0 2px 8px rgba(0,0,0,0.07);
            padding: 2.2rem 2.4rem 2rem;
            width: 100%;
            max-width: 820px;
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
            margin: 0 0 1.8rem;
        }
        .section-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: #c9a84c;
            border-bottom: 2px solid #0d1b2a;
            padding-bottom: 0.4rem;
            margin-bottom: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .field-group { margin-bottom: 0.9rem; }
        .field-group label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            color: #4a4a5a;
            margin-bottom: 0.3rem;
        }
        .input-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #d8d8e0;
            border-radius: 8px;
            padding: 0 10px;
            height: 36px;
            background: #fff;
            transition: border-color 0.15s;
        }
        .input-wrap:focus-within { border-color: #9ba4bc; }
        .input-wrap i { font-size: 15px; color: #b0b0bf; margin-right: 7px; flex-shrink: 0; }
        .input-wrap input,
        .input-wrap select {
            border: none;
            outline: none;
            font-size: 0.82rem;
            color: #2d3142;
            flex: 1;
            background: transparent;
            font-family: inherit;
        }
        .input-wrap input::placeholder { color: #c8c8d4; }
        .input-wrap.is-invalid { border-color: #dc3545; }
        /* select nativo sin flecha por defecto — la manejamos con el ícono */
        .input-wrap select { appearance: none; cursor: pointer; }
        .select-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #d8d8e0;
            border-radius: 8px;
            padding: 0 10px;
            height: 36px;
            background: #fff;
            transition: border-color 0.15s;
            position: relative;
        }
        .select-wrap:focus-within { border-color: #9ba4bc; }
        .select-wrap i.icon-left { font-size: 15px; color: #b0b0bf; margin-right: 7px; flex-shrink: 0; }
        .select-wrap i.icon-right { font-size: 13px; color: #b0b0bf; margin-left: 4px; flex-shrink: 0; pointer-events: none; }
        .select-wrap select {
            border: none;
            outline: none;
            font-size: 0.82rem;
            color: #2d3142;
            flex: 1;
            background: transparent;
            font-family: inherit;
            appearance: none;
            cursor: pointer;
        }
        .btn-register {
            display: block;
            width: 60%;
            margin: 1.6rem auto 0;
            padding: 0.65rem 0;
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
        .btn-register:hover { background: #1a2e45; }
        .btn-register:active { transform: scale(0.98); }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 0.9rem;
            font-size: 0.78rem;
            color: #9a9a9a;
            text-decoration: none;
        }
        .back-link:hover { color: #4a4a5a; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="register-card">

            <div class="logo-row">
                <img src="{{ asset('images/novaeventLogo.png') }}" class="logo-icon" alt="NovaEvent Logo">
                <div class="logo-text">NOVA<span>EVENT</span></div>
            </div>
            <p class="card-subtitle">Creación de Nuevo Usuario (Registro Inicial)</p>

            @if(session('error'))
                <div class="alert alert-danger small p-2 text-center mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger small p-2 mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" id="formRegistro" novalidate>
                @csrf

                <!-- DATOS DE PERSONA -->
                <div class="section-title">1. Datos de Persona</div>
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="field-group">
                            <label>DNI <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-id-badge" aria-hidden="true"></i>
                                <input type="text" name="dni" maxlength="13" required
                                       pattern="[0-9]+"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                       placeholder="Ej. 0801199901234"
                                       value="{{ old('dni') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Primer Nombre <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-user" aria-hidden="true"></i>
                                <input type="text" name="primer_nombre" maxlength="255" required
                                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                       oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');"
                                       placeholder="Primer nombre"
                                       value="{{ old('primer_nombre') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Segundo Nombre</label>
                            <div class="input-wrap">
                                <i class="ti ti-user" aria-hidden="true"></i>
                                <input type="text" name="segundo_nombre" maxlength="255"
                                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                       oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');"
                                       placeholder="Segundo nombre (opcional)"
                                       value="{{ old('segundo_nombre') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Apellido <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-user" aria-hidden="true"></i>
                                <input type="text" name="apellido" maxlength="255" required
                                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                       oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');"
                                       placeholder="Apellido"
                                       value="{{ old('apellido') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="field-group">
                            <label>Edad <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-calendar" aria-hidden="true"></i>
                                <input type="number" name="edad" min="0" max="127" required
                                       placeholder="Edad"
                                       value="{{ old('edad') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="field-group">
                            <label>Tipo <span class="text-danger">*</span></label>
                            <div class="select-wrap">
                                <i class="ti ti-building icon-left" aria-hidden="true"></i>
                                <select name="tip_persona" required>
                                    <option value="">Seleccionar…</option>
                                    <option value="N" {{ old('tip_persona') == 'N' ? 'selected' : '' }}>Natural</option>
                                    <option value="J" {{ old('tip_persona') == 'J' ? 'selected' : '' }}>Jurídica</option>
                                </select>
                                <i class="ti ti-chevron-down icon-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="field-group">
                            <label>Sexo <span class="text-danger">*</span></label>
                            <div class="select-wrap">
                                <i class="ti ti-gender-bigender icon-left" aria-hidden="true"></i>
                                <select name="sexo" required>
                                    <option value="">Seleccionar…</option>
                                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                    <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Otro</option>
                                    <option value="D" {{ old('sexo') == 'D' ? 'selected' : '' }}>No Dice</option>
                                </select>
                                <i class="ti ti-chevron-down icon-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="field-group">
                            <label>Estado Civil <span class="text-danger">*</span></label>
                            <div class="select-wrap">
                                <i class="ti ti-heart icon-left" aria-hidden="true"></i>
                                <select name="est_civil" required>
                                    <option value="">Seleccionar…</option>
                                    <option value="S" {{ old('est_civil') == 'S' ? 'selected' : '' }}>Soltero/a</option>
                                    <option value="C" {{ old('est_civil') == 'C' ? 'selected' : '' }}>Casado/a</option>
                                    <option value="V" {{ old('est_civil') == 'V' ? 'selected' : '' }}>Viudo/a</option>
                                </select>
                                <i class="ti ti-chevron-down icon-right" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- TIPO DE USUARIO -->
                <div class="section-title">2. Datos del Tipo de Usuario (Rol)</div>
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="field-group">
                            <label>Nombre del Rol <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-shield" aria-hidden="true"></i>
                                <input type="text" name="nom_tipo" maxlength="255" required
                                       pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                       placeholder="Ej. ADMINISTRADOR"
                                       value="{{ old('nom_tipo') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="field-group">
                            <label>Descripción del Rol <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-file-description" aria-hidden="true"></i>
                                <input type="text" name="des_tipo" maxlength="2000" required
                                       placeholder="Describe el rol y sus permisos…"
                                       value="{{ old('des_tipo') }}">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- CREDENCIALES -->
                <div class="section-title">3. Credenciales de Acceso</div>
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="field-group">
                            <label for="nombreUsr">Nombre de Usuario <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-at" aria-hidden="true"></i>
                                <input type="text" id="nombreUsr" name="nombreUsr" required
                                       placeholder="Nombre de usuario"
                                       value="{{ old('nombreUsr') }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="field-group">
                            <label for="clave">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-lock" aria-hidden="true"></i>
                                <input type="password" id="clave" name="clave" minlength="6" required
                                       placeholder="Mínimo 6 caracteres">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="field-group">
                            <label for="clave_confirmation">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <div class="input-wrap">
                                <i class="ti ti-lock-check" aria-hidden="true"></i>
                                <input type="password" id="clave_confirmation" name="clave_confirmation" minlength="6" required
                                       placeholder="Repita la contraseña">
                            </div>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn-register">Completar Registro</button>
                <a href="{{ route('login') }}" class="back-link">Volver al inicio de sesión</a>
            </form>
        </div>
    </div>

    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('#formRegistro')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>