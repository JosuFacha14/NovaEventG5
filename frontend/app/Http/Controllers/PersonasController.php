<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PersonasService;
use Throwable;

class PersonasController extends Controller
{
    public function __construct(protected PersonasService $svc) {}

    /* ================================================================== */
    /*  Gestión de Personas                                                 */
    /* ================================================================== */

    /** GET /personas — listado */
    public function index()
    {
        try {
            $personas = $this->svc->listarPersonas();
        } catch (Throwable $e) {
            $personas = [];
            session()->flash('error', 'No se pudo cargar la lista de personas: ' . $e->getMessage());
        }

        return view('personas.index', compact('personas'));
    }

    /** POST /personas — crear */
    public function store(Request $request)
    {
        $request->validate([
            'dni'          => 'required|string|max:255',
            'primer_nombre'=> 'required|string|max:255',
            'apellido'     => 'required|string|max:255',
            'sexo'         => 'required|in:M,F,O,D',
            'est_civil'    => 'required|in:S,C,V',
            'edad'         => 'required|integer|min:0|max:127',
            'tip_persona'  => 'required|in:N,J',
        ]);

        try {
            $this->svc->crearPersona(array_merge(
                $request->only([
                    'dni','primer_nombre','segundo_nombre','apellido',
                    'sexo','est_civil','edad','tip_persona',
                ]),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Persona creada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear persona: ' . $e->getMessage());
        }

        return redirect()->route('personas.index');
    }

    /** GET /personas/{id} — perfil/detalle */
    public function show(int $id)
    {
        try {
            $rows    = $this->svc->obtenerPersona($id);
            $persona = $rows[0] ?? null;

            if (!$persona) {
                session()->flash('error', 'Persona no encontrada.');
                return redirect()->route('personas.index');
            }

            // Peticiones paralelas de datos relacionados
            $telefonos  = $this->svc->obtenerTelefonosDePersona($id);
            $correos    = $this->svc->obtenerCorreosDePersona($id);
            $usuarios   = $this->svc->obtenerUsuarioDePersona($id);
            $clientes   = $this->svc->obtenerClienteDePersona($id);
            $empleados  = $this->svc->obtenerEmpleadoDePersona($id);
            $proveedores= $this->svc->obtenerProveedorDePersona($id);

        } catch (Throwable $e) {
            session()->flash('error', 'Error al cargar el perfil: ' . $e->getMessage());
            return redirect()->route('personas.index');
        }

        return view('personas.show', compact(
            'persona','telefonos','correos','usuarios','clientes','empleados','proveedores'
        ));
    }

    /** PUT /personas/{id} — editar */
    public function update(Request $request, int $id)
    {
        // Si es soft-delete, solo necesita el id
        if ($request->input('accion') === 'SOFT_DELETE') {
            try {
                $this->svc->desactivarPersona($id, session('usuario', 'admin'));
                session()->flash('success', 'Persona desactivada correctamente.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al desactivar: ' . $e->getMessage());
            }
            return redirect()->route('personas.index');
        }

        $request->validate([
            'primer_nombre'=> 'nullable|string|max:255',
            'apellido'     => 'nullable|string|max:255',
            'sexo'         => 'nullable|in:M,F,O,D',
            'est_civil'    => 'nullable|in:S,C,V',
            'edad'         => 'nullable|integer|min:0|max:127',
        ]);

        try {
            $this->svc->actualizarPersona($id, array_merge(
                $request->only([
                    'dni','primer_nombre','segundo_nombre','apellido',
                    'sexo','est_civil','edad',
                    'num_area_cel','num_telefono_cel',
                    'num_area_ofi','num_telefono_ofi',
                    'usuario_correo','servidor_correo',
                    'nombre_usr','clave','token','ind_usr','ind_primer_ing',
                    'nom_empresa_cli','ind_cliente',
                    'cargo','fec_contratacion','salario',
                    'empresa_prov','categoria_serv',
                ]),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Persona actualizada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar: ' . $e->getMessage());
        }

        return redirect()->route('personas.index');
    }

    /* ================================================================== */
    /*  Catálogo Tipos de Usuario                                           */
    /* ================================================================== */

    public function tiposUsuarioIndex()
    {
        try {
            $tipos = $this->svc->listarTiposUsuario();
        } catch (Throwable $e) {
            $tipos = [];
            session()->flash('error', 'No se pudo cargar tipos de usuario: ' . $e->getMessage());
        }

        return view('tipos-usuario.index', compact('tipos'));
    }

    public function tiposUsuarioStore(Request $request)
    {
        $request->validate([
            'nom_tipo' => 'required|string|max:255',
            'des_tipo' => 'required|string|max:2000',
        ]);

        try {
            $this->svc->crearTipoUsuario($request->only(['nom_tipo','des_tipo']));
            session()->flash('success', 'Tipo de usuario creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear tipo de usuario: ' . $e->getMessage());
        }

        return redirect()->route('tipos-usuario.index');
    }

    /* ================================================================== */
    /*  Catálogo Tipos de Cliente                                           */
    /* ================================================================== */

    public function tiposClienteIndex()
    {
        try {
            $tipos = $this->svc->listarTiposCliente();
        } catch (Throwable $e) {
            $tipos = [];
            session()->flash('error', 'No se pudo cargar tipos de cliente: ' . $e->getMessage());
        }

        return view('tipos-cliente.index', compact('tipos'));
    }

    public function tiposClienteStore(Request $request)
    {
        $request->validate([
            'nom_tipo_cli' => 'required|string|max:255',
            'des_tipo_cli' => 'required|string|max:255',
            'ind_tipo_cli' => 'required|in:1,0',
        ]);

        try {
            $this->svc->crearTipoCliente(array_merge(
                $request->only(['nom_tipo_cli','des_tipo_cli','ind_tipo_cli']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Tipo de cliente creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear tipo de cliente: ' . $e->getMessage());
        }

        return redirect()->route('tipos-cliente.index');
    }
      public function storeCorreo(Request $request, int $id)
{
    $request->validate([
        'usuario_correo'  => 'required|string|max:200',
        'servidor_correo' => 'required|string|max:200',
        'tip_correo'      => 'required|in:P,O',
    ]);
 
    try {
        $this->svc->agregarCorreo($id, array_merge(
            $request->only(['usuario_correo', 'servidor_correo', 'tip_correo']),
            ['usr_ingreso' => session('usuario', 'admin')]
        ));
        session()->flash('success', 'Correo agregado correctamente.');
    } catch (Throwable $e) {
        session()->flash('error', 'Error al agregar correo: ' . $e->getMessage());
    }
 
    return redirect()->route('personas.show', $id);
}
 
 
}