<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class AuthService
{
    protected string $base;

    public function __construct()
    {
        $this->base = rtrim(config('app.node_api_url', env('NODE_API_URL', 'http://localhost:3000')), '/') . '/api';
    }

    private function post(string $endpoint, array $body): array
    {
        $response = Http::timeout(10)->post("{$this->base}/{$endpoint}", $body);

        if ($response->failed()) {
            return [
                'error' => $response->json('mensaje') ?? $response->json('message') ?? $response->json('msg') ?? 'Error en la petición',
                'status' => $response->status()
            ];
        }

        return $response->json() ?? [];
    }

    private function get(string $endpoint, array $query = []): array
    {
        $response = Http::timeout(10)->get("{$this->base}/{$endpoint}", $query);

        if ($response->failed()) {
            return [
                'error' => $response->json('mensaje') ?? $response->json('message') ?? $response->json('msg') ?? 'Error en la petición',
                'status' => $response->status()
            ];
        }

        $data = $response->json();
        return is_array($data) ? $data : [];
    }

    private function put(string $endpoint, array $body): array
    {
        $response = Http::timeout(10)->put("{$this->base}/{$endpoint}", $body);

        if ($response->failed()) {
            return [
                'error' => $response->json('mensaje') ?? $response->json('message') ?? $response->json('msg') ?? 'Error en la petición',
                'status' => $response->status()
            ];
        }

        return $response->json() ?? [];
    }

    public function login(string $nombreUsr, string $clave): array
    {
        return $this->post('auth/login', [
            'nombreUsr' => $nombreUsr,
            'clave' => $clave
        ]);
    }

    public function register(array $data): array
    {
        return $this->post('auth/register', $data);
    }

    public function changePassword(array $data): array
    {
        return $this->put('auth/change-password', $data);
    }

    public function getUsuario(int $codUsuario): array
    {
        return $this->get("auth/usuario/{$codUsuario}");
    }

    public function validarNombreUsr(string $nombreUsr): array
    {
        return $this->get("auth/validar-nombre/{$nombreUsr}");
    }
}
