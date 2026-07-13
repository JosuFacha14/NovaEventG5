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

    /* ------------------------------------------------------------------ */
    /*  Helpers privados                                                    */
    /* ------------------------------------------------------------------ */

    private function get(string $endpoint, array $query = []): array
    {
        $response = Http::timeout(10)->get("{$this->base}/{$endpoint}", $query);

        if ($response->failed()) {
            throw new \RuntimeException($response->json('message') ?? 'Error al conectar con la API', $response->status());
        }

        $data = $response->json();

        // El SP devuelve un array; si viene null lo normalizamos
        return is_array($data) ? $data : [];
    }

    private function post(string $endpoint, array $body): array
    {
        $response = Http::timeout(10)->post("{$this->base}/{$endpoint}", $body);

        if ($response->failed()) {
            throw new \RuntimeException($response->json('message') ?? 'Error al insertar registro', $response->status());
        }

        return $response->json() ?? [];
    }

    private function put(string $endpoint, array $body): array
    {
        $response = Http::timeout(10)->put("{$this->base}/{$endpoint}", $body);

        if ($response->failed()) {
            throw new \RuntimeException($response->json('message') ?? 'Error al actualizar registro', $response->status());
        }

        return $response->json() ?? [];
    }

    /* ================================================================== */
    /*  PA_PERSONAS                                                         */
    /* ================================================================== */

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

    /* ================================================================== */
    /*  Datos de apoyo para el Perfil (show)                               */
    /* ================================================================== */

    public function obtenerTelefonosDePersona(int $idPersona): array
    {
        // 1) Obtener las relaciones persona-teléfono (solo trae COD_TELEFONO)
        $relaciones = $this->get('personas-telefonos', [
            'accion'      => 'SEL_REL_PERSONAS_TELEFONOS',
            'cod_persona' => $idPersona,
        ]);

        // 2) Enriquecer cada relación con los datos reales del teléfono
        $telefonos = [];
        foreach ($relaciones as $rel) {
            $codTel = $rel['COD_TELEFONO'] ?? null;
            if ($codTel && $codTel > 0) {
                try {
                    $datosTel = $this->get('telefonos', [
                        'accion'       => 'SEL_PA_TELEFONOS',
                        'cod_telefono' => $codTel,
                    ]);
                    // Combinar la relación con los datos del teléfono
                    $telefonos[] = array_merge($rel, $datosTel[0] ?? []);
                } catch (\Throwable $e) {
                    // Si falla, al menos mostrar lo que hay
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
        // 1) Obtener las relaciones persona-correo (solo trae COD_CORREO)
        $relaciones = $this->get('personas-correos', [
            'accion'      => 'SEL_REL_PERSONAS_CORREOS',
            'cod_persona' => $idPersona,
        ]);

        // 2) Enriquecer cada relación con los datos reales del correo
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
        // Paso 1: Insertar el teléfono en PA_TELEFONOS
        $telefono = $this->post('telefonos', array_merge(
            ['accion' => 'INS_TELEFONO'],
            $datos // num_area, num_telefono, tip_telefono, usr_ingreso
        ));

        $codTelefono = $telefono['NUEVO_ID'] ?? null;

        if (!$codTelefono) {
            throw new \RuntimeException(
                'El teléfono se insertó pero la API no devolvió el ID.'
            );
        }

        // Paso 2: Vincular el teléfono a la persona
        // El SP usa PI_COD_TIPO_USR (campo cod_tipo_usr) como COD_TELEFONO
        return $this->post('personas-telefonos', [
            'accion'       => 'INS_REL_PERSONA_TELEFONO',
            'cod_persona'  => $idPersona,
            'cod_tipo_usr' => $codTelefono,
            'usr_ingreso'  => $datos['usr_ingreso'] ?? 'admin',
        ]);
    }
 
    public function agregarCorreo(int $idPersona, array $datos): array
    {
        // Paso 1: Insertar el correo en PA_CORREOS
        $correo = $this->post('correos', array_merge(
            ['accion' => 'INS_CORREO'],
            $datos // usuario_correo, servidor_correo, tip_correo, usr_ingreso
        ));

        $codCorreo = $correo['NUEVO_ID'] ?? null;

        if (!$codCorreo) {
            throw new \RuntimeException(
                'El correo se insertó pero la API no devolvió el ID.'
            );
        }

        // Paso 2: Vincular el correo a la persona
        // El SP usa PI_COD_TIPO_USR (campo cod_tipo_usr) como COD_CORREO
        return $this->post('personas-correos', [
            'accion'       => 'INS_REL_PERSONA_CORREO',
            'cod_persona'  => $idPersona,
            'cod_tipo_usr' => $codCorreo,
            'usr_ingreso'  => $datos['usr_ingreso'] ?? 'admin',
        ]);
    }

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
    

    /* ================================================================== */
    /*  PA_TIPO_USUARIOS                                                    */
    /* ================================================================== */

    public function listarTiposUsuario(): array
    {
        return $this->get('tipos-usuarios', ['accion' => 'SEL_PA_TIPO_USUARIOS']);
    }

    public function crearTipoUsuario(array $datos): array
    {
        return $this->post('tipos-usuarios', array_merge(['accion' => 'INS_TIPO_USUARIO'], $datos));
    }

    /* ================================================================== */
    /*  PA_TIPO_CLIENTES                                                    */
    /* ================================================================== */

    public function listarTiposCliente(): array
    {
        return $this->get('tipos-clientes', ['accion' => 'SEL_PA_TIPO_CLIENTES']);
    }

    public function crearTipoCliente(array $datos): array
    {
        return $this->post('tipos-clientes', array_merge(['accion' => 'INS_TIPO_CLIENTE'], $datos));
    }
}