<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class PersonasService
{
    protected string $base;

    public function __construct()
    {
        $this->base = rtrim(config('app.node_api_url', env('NODE_API_URL', 'http://localhost:3000')), '/') . '/api';
    }

    
    //  Helpers privados                                                    
    

    private function get(string $endpoint, array $query = []): array
    {
        $response = Http::timeout(10)->get("{$this->base}/{$endpoint}", $query);

        if ($response->failed()) {
            throw new \RuntimeException(
                $response->json('message') ?? $response->json('msg') ?? 'Error al conectar con la API',
                $response->status()
            );
        }

        $data = $response->json();
        return is_array($data) ? $data : [];
    }

    private function post(string $endpoint, array $body): array
    {
        $response = Http::timeout(10)->post("{$this->base}/{$endpoint}", $body);

        if ($response->failed()) {
            throw new \RuntimeException(
                $response->json('message') ?? $response->json('msg') ?? 'Error al insertar registro',
                $response->status()
            );
        }

        return $response->json() ?? [];
    }

    private function put(string $endpoint, array $body): array
    {
        $response = Http::timeout(10)->put("{$this->base}/{$endpoint}", $body);

        if ($response->failed()) {
            throw new \RuntimeException(
                $response->json('message') ?? $response->json('msg') ?? 'Error al actualizar registro',
                $response->status()
            );
        }

        return $response->json() ?? [];
    }

 
    //  PA_PERSONAS                                                         
  

    public function listarPersonas(): array
    {
        return $this->get('personas', ['accion' => 'SEL_PA_PERSONAS']);
    }

    public function obtenerPersona(int $id): array
    {
        return $this->get("personas/{$id}", ['accion' => 'SEL_PA_PERSONAS', 'cod_persona' => $id]);
    }

    public function crearPersona(array $datos): array
    {
        return $this->post('personas', array_merge(['accion' => 'INS_PERSONA'], $datos));
    }

    public function actualizarPersona(int $id, array $datos): array
    {
        return $this->put("personas/{$id}", array_merge(
            ['accion' => 'UPDATE', 'cod_persona' => $id],
            $datos
        ));
    }

    public function desactivarPersona(int $id, string $usr): array
    {
        return $this->put("personas/{$id}", [
            'accion'      => 'SOFT_DELETE',
            'cod_persona' => $id,
            'usr_ingreso' => $usr,
        ]);
    }

    
    //  Teléfonos y Correos (relaciones con Persona)                       
    

    public function obtenerTelefonosDePersona(int $idPersona): array
    {
        $relaciones = $this->get('personas-telefonos', [
            'accion'      => 'SEL_REL_PERSONAS_TELEFONOS',
            'cod_persona' => $idPersona,
        ]);

        $telefonos = [];
        foreach ($relaciones as $rel) {
            $codTel = $rel['COD_TELEFONO'] ?? null;
            if ($codTel && $codTel > 0) {
                try {
                    $datosTel  = $this->get('telefonos', [
                        'accion'       => 'SEL_PA_TELEFONOS',
                        'cod_telefono' => $codTel,
                    ]);
                    $telefonos[] = array_merge($rel, $datosTel[0] ?? []);
                } catch (\Throwable $e) {
                    $telefonos[] = $rel;
                }
            } else {
                $telefonos[] = $rel;
            }
        }

        return $telefonos;
    }

    public function obtenerCorreosDePersona(int $idPersona): array
    {
        $relaciones = $this->get('personas-correos', [
            'accion'      => 'SEL_REL_PERSONAS_CORREOS',
            'cod_persona' => $idPersona,
        ]);

        $correos = [];
        foreach ($relaciones as $rel) {
            $codCor = $rel['COD_CORREO'] ?? null;
            if ($codCor && $codCor > 0) {
                try {
                    $datosCor = $this->get('correos', [
                        'accion'     => 'SEL_PA_CORREOS',
                        'cod_correo' => $codCor,
                    ]);
                    $correos[] = array_merge($rel, $datosCor[0] ?? []);
                } catch (\Throwable $e) {
                    $correos[] = $rel;
                }
            } else {
                $correos[] = $rel;
            }
        }

        return $correos;
    }

    public function agregarTelefono(int $idPersona, array $datos): array
    {
        $telefono = $this->post('telefonos', array_merge(
            ['accion' => 'INS_TELEFONO'],
            $datos
        ));

        $codTelefono = $telefono['NUEVO_ID'] ?? null;
        if (!$codTelefono) {
            throw new \RuntimeException('El teléfono se insertó pero la API no devolvió el ID.');
        }

        return $this->post('personas-telefonos', [
            'accion'       => 'INS_REL_PERSONA_TELEFONO',
            'cod_persona'  => $idPersona,
            'cod_tipo_usr' => $codTelefono,
            'usr_ingreso'  => $datos['usr_ingreso'] ?? 'admin',
        ]);
    }

    public function agregarCorreo(int $idPersona, array $datos): array
    {
        $correo = $this->post('correos', array_merge(
            ['accion' => 'INS_CORREO'],
            $datos
        ));

        $codCorreo = $correo['NUEVO_ID'] ?? null;
        if (!$codCorreo) {
            throw new \RuntimeException('El correo se insertó pero la API no devolvió el ID.');
        }

        return $this->post('personas-correos', [
            'accion'       => 'INS_REL_PERSONA_CORREO',
            'cod_persona'  => $idPersona,
            'cod_tipo_usr' => $codCorreo,
            'usr_ingreso'  => $datos['usr_ingreso'] ?? 'admin',
        ]);
    }

    
    //  Datos de apoyo para el Perfil (show)                               
    

    public function obtenerUsuarioDePersona(int $idPersona): array
    {
        return $this->get('usuarios', [
            'accion'      => 'SEL_USUARIOS',
            'cod_persona' => $idPersona,
        ]);
    }

    public function obtenerClienteDePersona(int $idPersona): array
    {
        return $this->get('clientes', [
            'accion'      => 'SEL_PA_CLIENTES',
            'cod_persona' => $idPersona,
        ]);
    }

    public function obtenerEmpleadoDePersona(int $idPersona): array
    {
        return $this->get('empleados', [
            'accion'      => 'SEL_PA_EMPLEADOS',
            'cod_persona' => $idPersona,
        ]);
    }

    public function obtenerProveedorDePersona(int $idPersona): array
    {
        return $this->get('proveedores', [
            'accion'      => 'SEL_PA_PROVEEDORES',
            'cod_persona' => $idPersona,
        ]);
    }

    // PA_TIPO_USUARIOS                                                    
    

    public function listarTiposUsuario(): array
    {
        return $this->get('tipos-usuarios', ['accion' => 'SEL_PA_TIPO_USUARIOS']);
    }

    public function obtenerTipoUsuario(int $id): array
    {
        return $this->get("tipos-usuarios/{$id}", [
            'accion'      => 'SEL_PA_TIPO_USUARIOS',
            'cod_tipo_usr' => $id,
        ]);
    }

    public function crearTipoUsuario(array $datos): array
    {
        return $this->post('tipos-usuarios', array_merge(['accion' => 'INS_TIPO_USUARIO'], $datos));
    }

    public function actualizarTipoUsuario(int $id, array $datos): array
    {
        return $this->put("tipos-usuarios/{$id}", array_merge(
            ['accion' => 'UPD_TIPO_USUARIO', 'cod_tipo_usr' => $id],
            $datos
        ));
    }

    
    //  PA_TIPO_CLIENTES                                                    
    

    public function listarTiposCliente(): array
    {
        return $this->get('tipos-clientes', ['accion' => 'SEL_PA_TIPO_CLIENTES']);
    }

    public function obtenerTipoCliente(int $id): array
    {
        return $this->get("tipos-clientes/{$id}", [
            'accion'      => 'SEL_PA_TIPO_CLIENTES',
            'cod_tipo_cli' => $id,
        ]);
    }

    public function crearTipoCliente(array $datos): array
    {
        return $this->post('tipos-clientes', array_merge(['accion' => 'INS_TIPO_CLIENTE'], $datos));
    }

    public function actualizarTipoCliente(int $id, array $datos): array
    {
        return $this->put("tipos-clientes/{$id}", array_merge(
            ['accion' => 'UPD_TIPO_CLIENTE', 'cod_tipo_cli' => $id],
            $datos
        ));
    }

    public function desactivarTipoCliente(int $id, string $usr): array
    {
        return $this->put("tipos-clientes/{$id}", [
            'accion'      => 'SOFT_DELETE_TIPO_CLI',
            'cod_tipo_cli' => $id,
            'usr_ingreso'  => $usr,
        ]);
    }

    
    //  USUARIOS 
    

    public function listarUsuarios(): array
    {
        return $this->get('usuarios', ['accion' => 'SEL_USUARIOS']);
    }

    public function obtenerUsuario(int $id): array
    {
        return $this->get("usuarios/{$id}", [
            'accion'      => 'SEL_USUARIOS',
            'cod_usuario' => $id,
        ]);
    }

    public function crearUsuario(array $datos): array
    {
        return $this->post('usuarios', array_merge(['accion' => 'INS_USUARIO'], $datos));
    }

    public function actualizarUsuario(int $idPersona, array $datos): array
    {
        // En base al SP UPD_PERSONAS, el ID usado para actualizar es COD_PERSONA.
        return $this->put("usuarios/{$idPersona}", array_merge(
            ['accion' => 'UPDATE', 'cod_persona' => $idPersona],
            $datos
        ));
    }

    public function desactivarUsuario(int $idPersona, string $usr): array
    {
        // desactiva al usuario.
        return $this->put("usuarios/{$idPersona}", [
            'accion'      => 'SOFT_DELETE',
            'cod_persona' => $idPersona,
            'usr_ingreso' => $usr,
        ]);
    }

    
    //  PA_CLIENTES 
    

    public function listarClientes(): array
    {
        return $this->get('clientes', ['accion' => 'SEL_PA_CLIENTES']);
    }

    public function obtenerCliente(int $id): array
    {
        return $this->get("clientes/{$id}", [
            'accion'      => 'SEL_PA_CLIENTES',
            'cod_cliente' => $id,
        ]);
    }

    public function crearCliente(array $datos): array
    {
        return $this->post('clientes', array_merge(['accion' => 'INS_CLIENTE'], $datos));
    }

    public function actualizarCliente(int $idPersona, array $datos): array
    {
        // En base al SP UPD_PERSONAS, el ID usado es COD_PERSONA.
        return $this->put("clientes/{$idPersona}", array_merge(
            ['accion' => 'UPDATE', 'cod_persona' => $idPersona],
            $datos
        ));
    }

    public function desactivarCliente(int $idPersona, string $usr): array
    {
        // UPDATE enviando ind_cliente = '0'.
        return $this->put("clientes/{$idPersona}", [
            'accion'      => 'UPDATE',
            'cod_persona' => $idPersona,
            'ind_cliente' => '0',
            'usr_ingreso' => $usr,
        ]);
    }

    
    //  PA_EMPLEADOS
    

    public function listarEmpleados(): array
    {
        return $this->get('empleados', ['accion' => 'SEL_PA_EMPLEADOS']);
    }

    public function obtenerEmpleado(int $id): array
    {
        return $this->get("empleados/{$id}", [
            'accion'       => 'SEL_PA_EMPLEADOS',
            'cod_empleado' => $id,
        ]);
    }

    public function crearEmpleado(array $datos): array
    {
        return $this->post('empleados', array_merge(['accion' => 'INS_EMPLEADO'], $datos));
    }

    public function actualizarEmpleado(int $idPersona, array $datos): array
    {
        return $this->put("empleados/{$idPersona}", array_merge(
            ['accion' => 'UPDATE', 'cod_persona' => $idPersona],
            $datos
        ));
    }

    public function desactivarEmpleado(int $idPersona, string $usr): array
    {
        // El SP no tiene un campo de estado para empleado.
        return $this->put("empleados/{$idPersona}", [
            'accion'       => 'UPDATE',
            'cod_persona'  => $idPersona,
            'usr_ingreso'  => $usr,
        ]);
    }

    
    // PA_PROVEEDORES
    

    public function listarProveedores(): array
    {
        return $this->get('proveedores', ['accion' => 'SEL_PA_PROVEEDORES']);
    }

    public function obtenerProveedor(int $id): array
    {
        return $this->get("proveedores/{$id}", [
            'accion'        => 'SEL_PA_PROVEEDORES',
            'cod_proveedor' => $id,
        ]);
    }

    public function crearProveedor(array $datos): array
    {
        return $this->post('proveedores', array_merge(['accion' => 'INS_PROVEEDOR'], $datos));
    }

    public function actualizarProveedor(int $idPersona, array $datos): array
    {
        return $this->put("proveedores/{$idPersona}", array_merge(
            ['accion' => 'UPDATE', 'cod_persona' => $idPersona],
            $datos
        ));
    }

    public function desactivarProveedor(int $idPersona, string $usr): array
    {
        return $this->put("proveedores/{$idPersona}", [
            'accion'        => 'UPDATE',
            'cod_persona'   => $idPersona,
            'usr_ingreso'   => $usr,
        ]);
    }
}