<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Estado;

class LandingController extends Controller
{
    public function index()
    {
        $users = User::all();
        $categorias = Categoria::all();
        $productos = Producto::all();
        $servicios = Servicio::all();
        $estados = Estado::all();

        // Registro de depuración
        \Log::info('Datos recuperados:', [
            'users' => $users,
            'categorias' => $categorias,
            'productos' => $productos,
            'servicios' => $servicios,
            'estados' => $estados,
        ]);

        return view('welcome', compact(
            'users',
            'categorias',
            'productos',
            'servicios',
            'estados'
        ));
    }

    public function servicios($id)
    {
        $servicio = Servicio::findOrFail($id); // Busca el servicio o falla
        return view('servicios', compact('servicio')); // Pasa el servicio a la vista
    }

    public function mostrarPorCategoria($categoriaId)
    {
        // Obtener la categoría seleccionada
        $categoria = Categoria::findOrFail($categoriaId);

        // Obtener los productos que pertenecen a la categoría seleccionada
        $productos = Producto::where('id_categoria', $categoriaId)->get();

        // Retornar la vista con los datos necesarios
        return view('productos', compact('categoria', 'productos'));
    }

    

}
