<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PersonasService;
use Throwable;

class PersonasController extends Controller
{
    public function __construct(protected PersonasService $svc) {}

    // PA_PERSONAS

    // GET /personas 
    public function index()
    {
        try {
            $personas = $this->svc->listarPersonas();
        } catch (Throwable $e) {
            $personas = [];
            session()->flash('error', 'No se pudo cargar la lista de personas: ' . $e->getMessage());
        }

        return view('mpersonas.personas.index', compact('personas'));
    }

    // POST /personas 
    public function store(Request $request)
    {
        $request->validate([
            'dni'           => 'required|string|max:255',
            'primer_nombre' => 'required|string|max:255',
            'apellido'      => 'required|string|max:255',
            'sexo'          => 'required|in:M,F,O,D',
            'est_civil'     => 'required|in:S,C,V',
            'edad'          => 'required|integer|min:0|max:127',
            'tip_persona'   => 'required|in:N,J',
        ]);

        try {
            $this->svc->crearPersona(array_merge(
                $request->only([
                    'dni', 'primer_nombre', 'segundo_nombre', 'apellido',
                    'sexo', 'est_civil', 'edad', 'tip_persona',
                ]),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Persona creada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear persona: ' . $e->getMessage());
        }

        return redirect()->route('personas.index');
    }

    // GET /personas/{id} 
    public function show(int $id)
    {
        try {
            $rows    = $this->svc->obtenerPersona($id);
            $persona = $rows[0] ?? null;

            if (!$persona) {
                session()->flash('error', 'Persona no encontrada.');
                return redirect()->route('personas.index');
            }

            $telefonos   = $this->svc->obtenerTelefonosDePersona($id);
            $correos     = $this->svc->obtenerCorreosDePersona($id);
            $usuarios    = $this->svc->obtenerUsuarioDePersona($id);
            $clientes    = $this->svc->obtenerClienteDePersona($id);
            $empleados   = $this->svc->obtenerEmpleadoDePersona($id);
            $proveedores = $this->svc->obtenerProveedorDePersona($id);

        } catch (Throwable $e) {
            session()->flash('error', 'Error al cargar el perfil: ' . $e->getMessage());
            return redirect()->route('personas.index');
        }

        return view('mpersonas.personas.show', compact(
            'persona', 'telefonos', 'correos', 'usuarios', 'clientes', 'empleados', 'proveedores'
        ));
    }

    // PUT /personas/{id} 
    public function update(Request $request, int $id)
    {
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
            'primer_nombre' => 'nullable|string|max:255',
            'apellido'      => 'nullable|string|max:255',
            'sexo'          => 'nullable|in:M,F,O,D',
            'est_civil'     => 'nullable|in:S,C,V',
            'edad'          => 'nullable|integer|min:0|max:127',
        ]);

        try {
            $this->svc->actualizarPersona($id, array_merge(
                $request->only([
                    'dni', 'primer_nombre', 'segundo_nombre', 'apellido',
                    'sexo', 'est_civil', 'edad',
                    'num_area_cel', 'num_telefono_cel',
                    'num_area_ofi', 'num_telefono_ofi',
                    'usuario_correo', 'servidor_correo',
                    'nombre_usr', 'clave', 'token', 'ind_usr', 'ind_primer_ing',
                    'nom_empresa_cli', 'ind_cliente',
                    'cargo', 'fec_contratacion', 'salario',
                    'empresa_prov', 'categoria_serv',
                ]),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Persona actualizada correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar: ' . $e->getMessage());
        }

        return redirect()->route('personas.index');
    }

    // POST /personas/{id}/telefonos 
    public function storeTelefono(Request $request, int $id)
    {
        $request->validate([
            'num_area'     => 'required|integer',
            'num_telefono' => 'required|integer',
            'tip_telefono' => 'required|in:C,O,P',
        ]);

        try {
            $this->svc->agregarTelefono($id, array_merge(
                $request->only(['num_area', 'num_telefono', 'tip_telefono']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Teléfono agregado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al agregar teléfono: ' . $e->getMessage());
        }

        return redirect()->route('personas.show', $id);
    }

    // POST /personas/{id}/correos 

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

    //  PA_TIPO_USUARIOS

    public function tiposUsuarioIndex()
    {
        try {
            $tipos = $this->svc->listarTiposUsuario();
        } catch (Throwable $e) {
            $tipos = [];
            session()->flash('error', 'No se pudo cargar tipos de usuario: ' . $e->getMessage());
        }

        return view('mpersonas.tipos-usuario.index', compact('tipos'));
    }

    public function tiposUsuarioStore(Request $request)
    {
        $request->validate([
            'nom_tipo' => 'required|string|max:255',
            'des_tipo' => 'required|string|max:2000',
        ]);

        try {
            $this->svc->crearTipoUsuario($request->only(['nom_tipo', 'des_tipo']));
            session()->flash('success', 'Tipo de usuario creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear tipo de usuario: ' . $e->getMessage());
        }

        return redirect()->route('tipos-usuario.index');
    }

    public function tiposUsuarioUpdate(Request $request, int $id)
    {
        $request->validate([
            'nom_tipo' => 'required|string|max:255',
            'des_tipo' => 'required|string|max:2000',
        ]);

        try {
            $this->svc->actualizarTipoUsuario($id, $request->only(['nom_tipo', 'des_tipo']));
            session()->flash('success', 'Tipo de usuario actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar tipo de usuario: ' . $e->getMessage());
        }

        return redirect()->route('tipos-usuario.index');
    }

    //  PA_TIPO_CLIENTES

    public function tiposClienteIndex()
    {
        try {
            $tipos = $this->svc->listarTiposCliente();
        } catch (Throwable $e) {
            $tipos = [];
            session()->flash('error', 'No se pudo cargar tipos de cliente: ' . $e->getMessage());
        }

        return view('mpersonas.tipos-cliente.index', compact('tipos'));
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
                $request->only(['nom_tipo_cli', 'des_tipo_cli', 'ind_tipo_cli']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Tipo de cliente creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear tipo de cliente: ' . $e->getMessage());
        }

        return redirect()->route('tipos-cliente.index');
    }

    public function tiposClienteUpdate(Request $request, int $id)
    {
        if ($request->input('accion') === 'SOFT_DELETE') {
            try {
                $this->svc->desactivarTipoCliente($id, session('usuario', 'admin'));
                session()->flash('success', 'Tipo de cliente desactivado correctamente.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al desactivar tipo de cliente: ' . $e->getMessage());
            }
            return redirect()->route('tipos-cliente.index');
        }

        $request->validate([
            'nom_tipo_cli' => 'required|string|max:255',
            'des_tipo_cli' => 'required|string|max:255',
            'ind_tipo_cli' => 'required|in:1,0',
        ]);

        try {
            $this->svc->actualizarTipoCliente($id, array_merge(
                $request->only(['nom_tipo_cli', 'des_tipo_cli', 'ind_tipo_cli']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Tipo de cliente actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar tipo de cliente: ' . $e->getMessage());
        }

        return redirect()->route('tipos-cliente.index');
    }

    //  USUARIOS

    public function usuariosIndex()
    {
        try {
            $usuarios     = $this->svc->listarUsuarios();
            $personas     = $this->svc->listarPersonas();
            $tiposUsuario = $this->svc->listarTiposUsuario();
        } catch (Throwable $e) {
            $usuarios = $personas = $tiposUsuario = [];
            session()->flash('error', 'No se pudo cargar usuarios: ' . $e->getMessage());
        }

        return view('mpersonas.usuarios.index', compact('usuarios', 'personas', 'tiposUsuario'));
    }

    public function usuariosStore(Request $request)
    {
        $request->validate([
            'cod_persona'   => 'required|integer|min:1',
            'cod_tipo_usr'  => 'required|integer|min:1',
            'nombre'        => 'required|string|max:255',
            'clave'         => 'required|string|min:6|max:2000',
            'token'         => 'required|string|size:6',
            'ind_usr'       => 'required|in:1,0',
            'ind_primer_ing'=> 'required|in:1,0',
        ]);

        try {
            $this->svc->crearUsuario(array_merge(
                $request->only([
                    'cod_persona', 'cod_tipo_usr', 'nombre',
                    'clave', 'token', 'ind_usr', 'ind_primer_ing',
                ]),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Usuario creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear usuario: ' . $e->getMessage());
        }

        return redirect()->route('usuarios.index');
    }

    public function usuariosUpdate(Request $request, int $id)
    {
        if ($request->input('accion') === 'SOFT_DELETE') {
            try {
                $this->svc->desactivarUsuario($id, session('usuario', 'admin'));
                session()->flash('success', 'Usuario desactivado correctamente.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al desactivar usuario: ' . $e->getMessage());
            }
            return redirect()->route('usuarios.index');
        }

        $request->validate([
            'cod_tipo_usr'  => 'required|integer|min:1',
            'nombre'        => 'required|string|max:255',
            'ind_usr'       => 'required|in:1,0',
            'ind_primer_ing'=> 'required|in:1,0',
        ]);

        try {
            $datos = $request->only([
                'cod_tipo_usr', 'ind_usr', 'ind_primer_ing',
            ]);
            $datos['nombre_usr'] = $request->input('nombre'); // Mapeado a nombre_usr para UPD_PERSONAS
            // Solo actualizar clave si se proporcionó
            if ($request->filled('clave')) {
                $request->validate(['clave' => 'string|min:6|max:2000']);
                $datos['clave'] = $request->input('clave');
            }
            $datos['usr_ingreso'] = session('usuario', 'admin');

            $this->svc->actualizarUsuario($id, $datos);
            session()->flash('success', 'Usuario actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }

        return redirect()->route('usuarios.index');
    }

    //  PA_CLIENTES

    public function clientesIndex()
    {
        try {
            $clientes    = $this->svc->listarClientes();
            $personas    = $this->svc->listarPersonas();
            $tiposCliente = $this->svc->listarTiposCliente();
        } catch (Throwable $e) {
            $clientes = $personas = $tiposCliente = [];
            session()->flash('error', 'No se pudo cargar clientes: ' . $e->getMessage());
        }

        return view('mpersonas.clientes.index', compact('clientes', 'personas', 'tiposCliente'));
    }

    public function clientesStore(Request $request)
    {
        $request->validate([
            'cod_persona'  => 'required|integer|min:1',
            'cod_tipo_cli' => 'required|integer|min:1',
            'nom_empresa'  => 'nullable|string|max:255',
            'ind_cliente'  => 'required|in:1,0',
        ]);

        try {
            $this->svc->crearCliente(array_merge(
                $request->only(['cod_persona', 'cod_tipo_cli', 'nom_empresa', 'ind_cliente']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Cliente creado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al crear cliente: ' . $e->getMessage());
        }

        return redirect()->route('clientes.index');
    }

    public function clientesUpdate(Request $request, int $id)
    {
        if ($request->input('accion') === 'SOFT_DELETE') {
            try {
                $this->svc->desactivarCliente($id, session('usuario', 'admin'));
                session()->flash('success', 'Cliente desactivado correctamente.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al desactivar cliente: ' . $e->getMessage());
            }
            return redirect()->route('clientes.index');
        }

        $request->validate([
            'cod_tipo_cli' => 'required|integer|min:1',
            'nom_empresa'  => 'nullable|string|max:255',
            'ind_cliente'  => 'required|in:1,0',
        ]);

        try {
            $datos = $request->only(['cod_tipo_cli', 'ind_cliente']);
            $datos['nom_empresa_cli'] = $request->input('nom_empresa'); // Mapeado para UPDATE

            $this->svc->actualizarCliente($id, array_merge(
                $datos,
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Cliente actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar cliente: ' . $e->getMessage());
        }

        return redirect()->route('clientes.index');
    }

    //  PA_EMPLEADOS

    public function empleadosIndex()
    {
        try {
            $empleados = $this->svc->listarEmpleados();
            $personas  = $this->svc->listarPersonas();
        } catch (Throwable $e) {
            $empleados = $personas = [];
            session()->flash('error', 'No se pudo cargar empleados: ' . $e->getMessage());
        }

        return view('mpersonas.empleados.index', compact('empleados', 'personas'));
    }

    public function empleadosStore(Request $request)
    {
        $request->validate([
            'cod_persona'     => 'required|integer|min:1',
            'cargo'           => 'required|string|max:100',
            'fec_contratacion'=> 'required|date',
            'salario'         => 'required|numeric|min:0',
        ]);

        try {
            $this->svc->crearEmpleado(array_merge(
                $request->only(['cod_persona', 'cargo', 'fec_contratacion', 'salario']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Empleado registrado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al registrar empleado: ' . $e->getMessage());
        }

        return redirect()->route('empleados.index');
    }

    public function empleadosUpdate(Request $request, int $id)
    {
        if ($request->input('accion') === 'SOFT_DELETE') {
            try {
                $this->svc->desactivarEmpleado($id, session('usuario', 'admin'));
                session()->flash('success', 'Empleado desactivado correctamente.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al desactivar empleado: ' . $e->getMessage());
            }
            return redirect()->route('empleados.index');
        }

        $request->validate([
            'cargo'           => 'required|string|max:100',
            'fec_contratacion'=> 'required|date',
            'salario'         => 'required|numeric|min:0',
        ]);

        try {
            $this->svc->actualizarEmpleado($id, array_merge(
                $request->only(['cargo', 'fec_contratacion', 'salario']),
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Empleado actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar empleado: ' . $e->getMessage());
        }

        return redirect()->route('empleados.index');
    }

    //  PA_PROVEEDORES 

    public function proveedoresIndex()
    {
        try {
            $proveedores = $this->svc->listarProveedores();
            $personas    = $this->svc->listarPersonas();
        } catch (Throwable $e) {
            $proveedores = $personas = [];
            session()->flash('error', 'No se pudo cargar proveedores: ' . $e->getMessage());
        }

        return view('mpersonas.proveedores.index', compact('proveedores', 'personas'));
    }

    public function proveedoresStore(Request $request)
    {
        $request->validate([
            'cod_persona'       => 'required|integer|min:1',
            'empresa'           => 'required|string|max:150',
            'categoria_servicio'=> 'nullable|string|max:100',
        ]);

        try {
            $datos = [
                'cod_persona'        => $request->input('cod_persona'),
                'empresa_prov'       => $request->input('empresa'), // Mapeado a empresa_prov
                'categoria_servicio' => $request->input('categoria_servicio'),
            ];

            $this->svc->crearProveedor(array_merge(
                $datos,
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Proveedor registrado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al registrar proveedor: ' . $e->getMessage());
        }

        return redirect()->route('proveedores.index');
    }

    public function proveedoresUpdate(Request $request, int $id)
    {
        if ($request->input('accion') === 'SOFT_DELETE') {
            try {
                $this->svc->desactivarProveedor($id, session('usuario', 'admin'));
                session()->flash('success', 'Proveedor desactivado correctamente.');
            } catch (Throwable $e) {
                session()->flash('error', 'Error al desactivar proveedor: ' . $e->getMessage());
            }
            return redirect()->route('proveedores.index');
        }

        $request->validate([
            'empresa'           => 'required|string|max:150',
            'categoria_servicio'=> 'nullable|string|max:100',
        ]);

        try {
            $datos = [
                'empresa_prov'   => $request->input('empresa'),
                'categoria_serv' => $request->input('categoria_servicio'), // Para UPDATE usa _serv
            ];

            $this->svc->actualizarProveedor($id, array_merge(
                $datos,
                ['usr_ingreso' => session('usuario', 'admin')]
            ));
            session()->flash('success', 'Proveedor actualizado correctamente.');
        } catch (Throwable $e) {
            session()->flash('error', 'Error al actualizar proveedor: ' . $e->getMessage());
        }

        return redirect()->route('proveedores.index');
    }
}