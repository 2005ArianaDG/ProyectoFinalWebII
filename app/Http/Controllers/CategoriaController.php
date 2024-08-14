<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Log;


class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('dashboard', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Categoria::create([
            'name' => $request->name,
            'descripcion' => $request->descripcion,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('dashboard')->with('success', 'Noticia creada con éxito');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return response()->json($categoria);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->name = $request->name;
        $categoria->descripcion = $request->descripcion;
        $categoria->save();
        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->noticias()->detach(); // Elimina la relación en la tabla intermedia
        $categoria->delete();
        return redirect()->route('dashboard')->with('success', 'Categoría eliminada correctamente.');
    }

    public function showItems($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $items = $categoria->items->chunk(4);
            
            dd($items); // Depura el contenido de $items
            
            return view('items-carousel', ['items' => $items]);
        } catch (\Exception $e) {
            // Registra el error para depuración
            Log::error('Error en showItems: ' . $e->getMessage());
            return abort(500, 'Error en el servidor.');
        }
    }
}
