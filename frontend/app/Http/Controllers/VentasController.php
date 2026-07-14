<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VentasController extends Controller
{
    /**
     * URL BASE DE LA API
     */
    private $api = 'http://localhost:3000/api';


    /*=========================================================
    | CATEGORÍAS DE EVENTO
    =========================================================*/

    public function categoriasIndex()
    {
        try {

            $response = Http::get(
                $this->api . '/categorias-eventos',
                [
                    'accion' => 'SEL_CATEGORIA'
                ]
            );

            $categorias = $response->successful()
                ? $response->json()
                : [];

        } catch (\Exception $e) {

            $categorias = [];

            return back()->with(
                'error',
                'No fue posible conectar con la API.'
            );
        }

        return view(
            'mventas.categorias-evento.index',
            compact('categorias')
        );
    }


    public function categoriasStore(Request $request)
    {
        $request->validate([
            'nom_categoria' => 'required|max:100',
            'des_categoria' => 'required|max:255'
        ]);

        try {

            $response = Http::post(
                $this->api . '/categorias-eventos',
                [
                    'accion' => 'INS_CATEGORIA',
                    'nom_categoria' => $request->nom_categoria,
                    'des_categoria' => $request->des_categoria
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('categorias-evento.index')
                    ->with(
                        'success',
                        'Categoría creada correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible guardar el registro.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    public function categoriasUpdate(Request $request, $id)
    {
        $request->validate([
            'nom_categoria' => 'required|max:100',
            'des_categoria' => 'required|max:255'
        ]);

        try {

            $response = Http::put(
                $this->api . '/categorias-eventos/' . $id,
                [
                    'accion' => 'UPD_CATEGORIA',
                    'nom_categoria' => $request->nom_categoria,
                    'des_categoria' => $request->des_categoria
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('categorias-evento.index')
                    ->with(
                        'success',
                        'Categoría actualizada correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible actualizar.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    public function categoriasDestroy($id)
    {
        try {

            $response = Http::put(
                $this->api . '/categorias-eventos/' . $id,
                [
                    'accion' => 'DEL_CATEGORIA'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('categorias-evento.index')
                    ->with(
                        'success',
                        'Categoría eliminada correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible eliminar.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    /*=========================================================
    | CICLOS DE EVENTO
    =========================================================*/

    /**
     * Mostrar listado de ciclos
     */
    public function ciclosIndex()
    {
        try {

            $response = Http::get(
                $this->api . '/ciclos-eventos',
                [
                    'accion' => 'SEL_CICLO'
                ]
            );

            $ciclos = $response->successful()
                ? $response->json()
                : [];

        } catch (\Exception $e) {

            $ciclos = [];

            return back()->with(
                'error',
                'No fue posible conectar con la API.'
            );
        }

        return view(
            'mventas.ciclos-evento.index',
            compact('ciclos')
        );
    }


    /**
     * Guardar ciclo de evento
     */
    public function ciclosStore(Request $request)
    {
        $request->validate([
            'nom_ciclo' => 'required|max:100',
            'des_ciclo' => 'required|max:255'
        ]);

        try {

            $response = Http::post(
                $this->api . '/ciclos-eventos',
                [
                    'accion' => 'INS_CICLO',
                    'nom_ciclo' => $request->nom_ciclo,
                    'des_ciclo' => $request->des_ciclo,

                    // Cambiar cuando exista autenticación real
                    'usr_ingreso' => 'ADMIN'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('ciclos-evento.index')
                    ->with(
                        'success',
                        'Ciclo de evento creado correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible guardar el ciclo de evento.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    /**
     * Actualizar ciclo de evento
     */
    public function ciclosUpdate(Request $request, $id)
    {
        $request->validate([
            'nom_ciclo' => 'required|max:100',
            'des_ciclo' => 'required|max:255'
        ]);

        try {

            $response = Http::put(
                $this->api . '/ciclos-eventos/' . $id,
                [
                    'accion' => 'UPD_CICLO',
                    'nom_ciclo' => $request->nom_ciclo,
                    'des_ciclo' => $request->des_ciclo,

                    // Mantener activo durante la edición
                    'ind_activo_ciclo' => '1'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('ciclos-evento.index')
                    ->with(
                        'success',
                        'Ciclo de evento actualizado correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible actualizar el ciclo de evento.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }


    /**
     * Baja lógica del ciclo de evento
     */
    public function ciclosDestroy($id)
    {
        try {

            $response = Http::put(
                $this->api . '/ciclos-eventos/' . $id,
                [
                    'accion' => 'DEL_CICLO'
                ]
            );

            if ($response->successful()) {

                return redirect()
                    ->route('ciclos-evento.index')
                    ->with(
                        'success',
                        'Ciclo de evento dado de baja correctamente.'
                    );
            }

            return back()->with(
                'error',
                'No fue posible dar de baja el ciclo de evento.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Error al conectar con la API.'
            );
        }
    }
}