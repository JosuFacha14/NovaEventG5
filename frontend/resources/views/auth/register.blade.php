<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - NovaEvent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            padding: 2rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            width: 100%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .section-title {
            color: #c9a84c;
            border-bottom: 2px solid #0d1b2a;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center">
        <div class="card login-card border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-0">NOVA<span style="color:#c9a84c;">EVENT</span></h4>
                    <p class="text-muted small">Creación de Nuevo Usuario (Registro Inicial)</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger small p-2 text-center">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger small p-2">
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
                            <label class="form-label small fw-bold">DNI <span class="text-danger">*</span></label>
                            <input type="text" name="dni" class="form-control form-control-sm" maxlength="13" required 
                                   pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="{{ old('dni') }}">
                            <div class="invalid-feedback small">El DNI es obligatorio y numérico.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Primer Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="primer_nombre" class="form-control form-control-sm" maxlength="255" required
                                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');" value="{{ old('primer_nombre') }}">
                            <div class="invalid-feedback small">Obligatorio y solo letras.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Segundo Nombre</label>
                            <input type="text" name="segundo_nombre" class="form-control form-control-sm" maxlength="255"
                                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');" value="{{ old('segundo_nombre') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Apellido <span class="text-danger">*</span></label>
                            <input type="text" name="apellido" class="form-control form-control-sm" maxlength="255" required
                                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');" value="{{ old('apellido') }}">
                            <div class="invalid-feedback small">Obligatorio y solo letras.</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Edad <span class="text-danger">*</span></label>
                            <input type="number" name="edad" class="form-control form-control-sm" min="0" max="127" required value="{{ old('edad') }}">
                            <div class="invalid-feedback small">Obligatorio (0-127).</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Tipo <span class="text-danger">*</span></label>
                            <select name="tip_persona" class="form-select form-select-sm" required>
                                <option value="">Seleccionar…</option>
                                <option value="N" {{ old('tip_persona') == 'N' ? 'selected' : '' }}>Natural</option>
                                <option value="J" {{ old('tip_persona') == 'J' ? 'selected' : '' }}>Jurídica</option>
                            </select>
                            <div class="invalid-feedback small">Requerido.</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Sexo <span class="text-danger">*</span></label>
                            <select name="sexo" class="form-select form-select-sm" required>
                                <option value="">Seleccionar…</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Otro</option>
                                <option value="D" {{ old('sexo') == 'D' ? 'selected' : '' }}>No Dice</option>
                            </select>
                            <div class="invalid-feedback small">Requerido.</div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Estado Civil <span class="text-danger">*</span></label>
                            <select name="est_civil" class="form-select form-select-sm" required>
                                <option value="">Seleccionar…</option>
                                <option value="S" {{ old('est_civil') == 'S' ? 'selected' : '' }}>Soltero/a</option>
                                <option value="C" {{ old('est_civil') == 'C' ? 'selected' : '' }}>Casado/a</option>
                                <option value="V" {{ old('est_civil') == 'V' ? 'selected' : '' }}>Viudo/a</option>
                            </select>
                            <div class="invalid-feedback small">Requerido.</div>
                        </div>
                    </div>

                    <!-- TIPO DE USUARIO -->
                    <div class="section-title">2. Datos del Tipo de Usuario (Rol)</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Nombre del Rol <span class="text-danger">*</span></label>
                            <input type="text" name="nom_tipo" class="form-control form-control-sm" maxlength="255" required
                                   pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" placeholder="Ej. ADMINISTRADOR" value="{{ old('nom_tipo') }}">
                            <div class="invalid-feedback small">Solo letras.</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Descripción del Rol <span class="text-danger">*</span></label>
                            <input type="text" name="des_tipo" class="form-control form-control-sm" maxlength="2000" required
                                      placeholder="Describe el rol y sus permisos…" value="{{ old('des_tipo') }}">
                            <div class="invalid-feedback small">La descripción es obligatoria.</div>
                        </div>
                    </div>

                    <!-- DATOS DEL USUARIO -->
                    <div class="section-title">3. Credenciales de Acceso</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="nombreUsr" class="form-label small fw-bold">Nombre de Usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="nombreUsr" name="nombreUsr" value="{{ old('nombreUsr') }}" required>
                            <div class="invalid-feedback small">Requerido.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="clave" class="form-label small fw-bold">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" id="clave" name="clave" minlength="6" required>
                            <div class="invalid-feedback small">Mínimo 6 caracteres.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="clave_confirmation" class="form-label small fw-bold">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control form-control-sm" id="clave_confirmation" name="clave_confirmation" minlength="6" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2 py-2 fw-bold" style="background-color: #0d1b2a; border-color: #0d1b2a;">Completar Registro</button>
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">Volver al inicio de sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Validación de Bootstrap en frontend
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
