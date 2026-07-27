<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InventarioService
{
    protected string $base;

    public function __construct()
    {
        $this->base = rtrim(config('app.node_api_url', env('NODE_API_URL', 'http://localhost:3000')), '/') . '/api';
    }

    // -------------------------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------------------------

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

    // -------------------------------------------------------------------------
    // IN_CATEGORIAS_INVENTARIO
    // -------------------------------------------------------------------------

    public function listarCategorias(): array
    {
        return $this->get('in/categoria');
    }

    public function crearCategoria(array $datos): array
    {
        return $this->post('in/item', $datos);
    }

    public function actualizarCategoria(int $id, array $datos): array
    {
        return $this->put("in/categoria/{$id}", $datos);
    }

    public function darBajaCategoria(int $id, string $usrRegistro): array
    {
        return $this->put("in/categoria/{$id}/estado", ['usr_registro' => $usrRegistro]);
    }

    // -------------------------------------------------------------------------
    // IN_ALMACENES
    // -------------------------------------------------------------------------

    public function listarAlmacenes(): array
    {
        return $this->get('in/almacen');
    }

    public function crearAlmacen(array $datos): array
    {
        return $this->post('in/item', $datos);
    }

    public function actualizarAlmacen(int $id, array $datos): array
    {
        return $this->put("in/almacen/{$id}", $datos);
    }

    public function darBajaAlmacen(int $id, string $usrRegistro): array
    {
        return $this->put("in/almacen/{$id}/estado", ['usr_registro' => $usrRegistro]);
    }

    // -------------------------------------------------------------------------
    // IN_INVENTARIO_ITEM
    // -------------------------------------------------------------------------

    public function listarItems(): array
    {
        return $this->get('in/item');
    }

    public function obtenerItem(int $id): array
    {
        return $this->get("in/item/{$id}");
    }

    public function crearItem(array $datos): array
    {
        return $this->post('in/item', $datos);
    }

    public function actualizarItem(int $id, array $datos): array
    {
        return $this->put("in/item/{$id}", $datos);
    }

    public function darBajaItem(int $id, string $usrRegistro): array
    {
        return $this->put("in/item/{$id}/estado", ['usr_registro' => $usrRegistro]);
    }

    // -------------------------------------------------------------------------
    // IN_RESERVAS_INVENTARIO
    // -------------------------------------------------------------------------

    public function listarReservas(): array
  {
    return $this->get('in/reserva');
  }

   public function actualizarReserva(int $id, array $datos): array
  {
    return $this->put("in/reserva/{$id}", $datos);
  }

    // -------------------------------------------------------------------------
    // IN_ASIGNACION_EVENTO
    // -------------------------------------------------------------------------

    public function listarAsignaciones(): array
    {
        return $this->get('in/asignacion');
    }

    public function listarAsignacionesPorEvento(int $codEvento): array
    {
        return $this->get("in/evento/{$codEvento}");
    }

    public function crearItemConAsignacion(array $datos): array
    {
        return $this->post('in/item', $datos);
    }

    public function actualizarAsignacion(int $id, array $datos): array
    {
        return $this->put("in/asignacion/{$id}", $datos);
    }

    public function listarEventos(): array
   {
    return $this->get('in/evento');
   }
}
