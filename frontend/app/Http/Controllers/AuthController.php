<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\PersonasService;

class AuthController extends Controller
{
    protected $authService;
    protected $personasService;

    public function __construct(AuthService $authService, PersonasService $personasService)
    {
        $this->authService = $authService;
        $this->personasService = $personasService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            // Persona
            'dni' => 'required|string|max:255',
            'primer_nombre' => 'required|string|max:255',
            'segundo_nombre' => 'nullable|string|max:255',
            'apellido' => 'required|string|max:255',
            'edad' => 'required|integer|min:0|max:127',
            'tip_persona' => 'required|string',
            'sexo' => 'required|string',
            'est_civil' => 'required|string',
            
            // Tipo de usuario
            'nom_tipo' => 'required|string|max:255',
            'des_tipo' => 'required|string|max:2000',
            
            // Usuario
            'nombreUsr' => 'required|string',
            'clave' => 'required|string|min:6|confirmed',
        ]);

        try {
            // 1. Crear Persona
            $personaData = [
                'dni' => $request->dni,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'apellido' => $request->apellido,
                'edad' => $request->edad,
                'tip_persona' => $request->tip_persona,
                'sexo' => $request->sexo,
                'est_civil' => $request->est_civil,
                'usr_ingreso' => 'SYSTEM'
            ];
            $resPersona = $this->personasService->crearPersona($personaData);
            $codPersona = $resPersona['NUEVO_ID'] ?? $resPersona['nuevo_id'] ?? null;

            if (!$codPersona) {
                return back()->with('error', 'No se pudo obtener el ID de la persona creada.')->withInput();
            }

            // 2. Crear Tipo de Usuario
            $tipoUsrData = [
                'nom_tipo' => $request->nom_tipo,
                'des_tipo' => $request->des_tipo,
                'usr_ingreso' => 'SYSTEM'
            ];
            $resTipoUsr = $this->personasService->crearTipoUsuario($tipoUsrData);
            $codTipoUsr = $resTipoUsr['NUEVO_ID'] ?? $resTipoUsr['nuevo_id'] ?? null;

            if (!$codTipoUsr) {
                return back()->with('error', 'No se pudo obtener el ID del tipo de usuario creado.')->withInput();
            }

            // 3. Crear Usuario
            $data = [
                'codPersona' => $codPersona,
                'codTipoUsr' => $codTipoUsr,
                'nombreUsr' => $request->nombreUsr,
                'clave' => $request->clave,
                'usrIngreso' => 'SYSTEM'
            ];

            $result = $this->authService->register($data);

            if (isset($result['error'])) {
                return back()->with('error', $result['error'])->withInput();
            }

            return redirect()->route('login')->with('success', 'Cuenta creada exitosamente. Puede iniciar sesión (se le pedirá cambiar clave).');
        } catch (\Exception $e) {
            return back()->with('error', 'Error en el proceso: ' . $e->getMessage())->withInput();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'nombre_usr' => 'required|string',
            'clave' => 'required|string',
        ]);

        $result = $this->authService->login($request->input('nombre_usr'), $request->input('clave'));

        if (isset($result['error'])) {
            return back()->with('error', $result['error'])->withInput();
        }

        $usuario = $result['usuario'] ?? [];
        $token = $result['token'] ?? null;
        $requiereCambio = $result['requiereCambioClave'] ?? false;

        if ($requiereCambio) {
            // Actualizar la base de datos para quitar el flag de primer ingreso
            // reutilizando la misma contraseña que acaban de ingresar
            $this->authService->changePassword([
                'codUsuario' => $usuario['COD_USUARIO'] ?? $usuario['cod_usuario'] ?? null,
                'claveNueva' => $request->input('clave'),
                'tokenNuevo' => '',
                'usrIngreso' => 'SYSTEM'
            ]);
            
            // Actualizar el flag en la sesión
            $requiereCambio = false;
        }

        session([
            'usuario' => $usuario,
            'api_token' => $token,
            'requiere_cambio_clave' => $requiereCambio
        ]);

        return redirect()->route('dashboard')->with('success', '¡Bienvenido al sistema! Has completado el ingreso exitosamente.');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|min:6|confirmed',
        ]);

        $usuario = session('usuario');
        if (!$usuario) {
            return redirect()->route('login');
        }

        $data = [
            'codUsuario' => $usuario['COD_USUARIO'] ?? $usuario['cod_usuario'] ?? null,
            'claveNueva' => $request->input('clave'),
            'tokenNuevo' => '',
            'usrIngreso' => 'SYSTEM'
        ];

        $result = $this->authService->changePassword($data);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        session(['requiere_cambio_clave' => false]);
        return redirect()->route('dashboard')->with('success', 'Contraseña actualizada exitosamente.');
    }
}
