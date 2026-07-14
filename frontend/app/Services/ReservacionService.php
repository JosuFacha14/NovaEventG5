<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class ReservacionService
{
    protected string $base;

    public function __construct()
    {
        $this->base = rtrim(config('app.node_api_url', env('NODE_API_URL', 'http://localhost:3000')), '/') . '/api';
    }

    //Helpers privados

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

    //RE_ESPACIO

    public function listarEspacios(): array
    {
        return $this->get('re/espacio');
    }

    public function obtenerEspacio(int $id): array
    {
        return $this->get("re/espacio/{$id}");
    }

    public function crearEspacio(array $datos): array
    {
        return $this->post('re/espacio', $datos);
    }

    public function actualizarEspacio(int $id, array $datos): array
    {
        return $this->put("re/espacio/{$id}", $datos);
    }

    public function cambiarEstadoEspacio(int $id, string $estado): array
    {
        return $this->put("re/espacio/{$id}/estado", ['ind_estado' => $estado]);
    }

    //RE_RESERVACION

    public function listarReservaciones(): array
    {
        return $this->get('re/reservacion');
    }

    public function obtenerReservacion(int $id): array
    {
        return $this->get("re/reservacion/{$id}");
    }

    public function crearReservacion(array $datos): array
    {
        return $this->post('re/reservacion', $datos);
    }

    public function actualizarReservacion(int $id, array $datos): array
    {
        return $this->put("re/reservacion/{$id}", $datos);
    }

    public function cambiarEstadoReservacion(int $id, string $estado): array
    {
        return $this->put("re/reservacion/{$id}/estado", ['ind_estado' => $estado]);
    }

    //RE_ESPACIO_OCUPADO

    public function listarEspaciosOcupados(): array
    {
        return $this->get('re/espacio-ocupado');
    }

    public function obtenerEspacioOcupado(int $id): array
    {
        return $this->get("re/espacio-ocupado/{$id}");
    }

    public function crearEspacioOcupado(array $datos): array
    {
        return $this->post('re/espacio-ocupado', $datos);
    }

    public function actualizarEspacioOcupado(int $id, array $datos): array
    {
        return $this->put("re/espacio-ocupado/{$id}", $datos);
    }

    // RE_HISTORIAL_RESERVACION

    public function listarHistorial(): array
    {
        return $this->get('re/historial');
    }

    public function listarHistorialPorReservacion(int $codReservacion): array
    {
        return $this->get("re/historial/reservacion/{$codReservacion}");
    }

    public function crearHistorial(array $datos): array
    {
        return $this->post('re/historial', $datos);
    }
}
