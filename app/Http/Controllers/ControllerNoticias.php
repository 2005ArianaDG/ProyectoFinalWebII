<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Noticia;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class ControllerNoticias extends Controller
{

    public function index2()
    {
        $noticias = Noticia::all();
        $categorias = Categoria::all(); // Obtén las categorías si es necesario
        if (Auth::check()) {
            return view('dashboard', compact('noticias', 'categorias'));
        }
        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function admin()
    {
        return view('admin');
    }

    public function newNoti()
    {
        return view('content.newNoti');
    }

    public function login()
    {
        return view('content.login');
    }

    public function newRegistro()
    {
        return view('content.registrar');
    }

    public function dataTable()
    {
        return view('content.datos');
    }

    public function dataTable2()
    {
        try {
            // Asegúrate de devolver solo el contenido necesario del div
            return response()->view('content.datos2')->render();
        } catch (\Exception $e) {
            // Registra el error para depuración
            return response()->json(['error' => 'Error loading data'], 500);
        }
    }

    public function newCategoria()
    {
        return view('content.newCategoria');
    }

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'codigo' => 'nullable|integer', // Asegúrate de que el campo 'codigo' esté presente en el formulario
        ]);

        // Verificar si el correo ya existe en la base de datos
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'El correo electrónico ya está en uso.'], 409); // 409 Conflict
        }

        // Obtener el primer registro de la tabla users
        $firstUser = User::find(1);

        // Determinar el valor del código
        $codigo = $request->codigo;
        // Convertir a string para comparación
        $firstUserCode = strval($firstUser->codigo);
        $requestCodigo = strval($request->codigo);

        // Determinar el valor del código
        $codigo = $requestCodigo;
        if ($firstUserCode !== $requestCodigo && $request->error === '1') {
            error_log('Final Code1: ' . $codigo);
            return response()->json(['message' => 'El codigo es incorrecto.'], 409); // 409 Conflict
        } else if ($firstUserCode === $requestCodigo && $request->error === '1') {
            error_log('Final Code2: ' . $codigo);
        } else {
            error_log('Final Code3: ' . $codigo);
            $codigo = '1';
        }

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'codigo' => $codigo,
        ]);

        // Retornar una respuesta JSON indicando éxito
        return response()->json(['message' => 'User registered successfully', 'user' => $user]);
    }

    public function loginSession(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Buscar al usuario por su correo electrónico
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$user) {
            return response()->json(['message' => 'El correo no existe', 'number' => 2]); // 401 Unauthorized
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Contraseña inválida', 'number' => 2]); // 401 Unauthorized
        }

        // Determinar el número basado en el código del usuario
        if ($user->codigo !== 1) {
            return response()->json(['message' => 'Inicio de sesión exitoso ad', 'number' => 1]);
        } else {
            // Retornar una respuesta JSON indicando éxito y el número correspondiente
            return response()->json(['message' => 'Inicio de sesión exitoso n', 'number' => 2]);
        }
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('noticias.create', compact('categorias'));
    }

    public function getNoticias()
    {
        // Obtener todas las noticias junto con las categorías relacionadas
        $noticias = Noticia::with('categorias')->get()->map(function ($noticia) {
            return [
                'id' => $noticia->id,
                'titulo' => $noticia->titulo,
                'texto' => $noticia->texto,
                'linkIMG' => $noticia->linkIMG,
                'categorias' => $noticia->categorias->map(function ($categoria) {
                    return [
                        'name' => $categoria->name,
                    ];
                }),
            ];
        });

        return response()->json($noticias);
    }


    public function AllCategorias()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    public function getNoticiaById($id)
    {
        $noticia = Noticia::with('categorias')->findOrFail($id);

        return response()->json([
            'id' => $noticia->id,
            'titulo' => $noticia->titulo,
            'texto' => $noticia->texto,
            'linkIMG' => $noticia->linkIMG,
            'categorias' => $noticia->categorias->map(function ($categoria) {
                return [
                    'name' => $categoria->name,
                ];
            }),
            'created_at' => $noticia->created_at,
            'updated_at' => $noticia->updated_at
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:45',
            'texto' => 'required|max:500',
            'image_option' => 'required',
            'linkIMG' => 'required_if:image_option,url|nullable|url|max:200',
            'image_file' => 'required_if:image_option,upload|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
        ]);

        $linkIMG = null;

        if ($request->image_option == 'upload') {
            $image = $request->file('image_file');
            $imagePath = $image->store('img', 'public');
            $linkIMG = Storage::url($imagePath);
        } else {
            $linkIMG = $request->linkIMG;
        }

        $noticia = Noticia::create([
            'titulo' => $request->titulo,
            'texto' => $request->texto,
            'linkIMG' => $linkIMG,
        ]);

        if ($request->has('categorias')) {
            $noticia->categorias()->attach($request->categorias);
        }

        return redirect()->route('dashboard')->with('success', 'Noticia creada con éxito');
    }

    public function update(Request $request, $id)
    {
        // Encontrar la noticia por ID o lanzar una excepción si no existe
        $noticia = Noticia::findOrFail($id);

        // Validar los datos de entrada
        $request->validate([
            'titulo' => 'required|max:45',
            'texto' => 'required|max:500',
            'image_option' => 'required',
            'linkIMG' => 'nullable|url|max:200',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
        ]);

        // Manejo de la imagen
        if ($request->input('image_option') === 'url') {
            // Si se elige URL y se proporciona una, actualizar el linkIMG
            $noticia->linkIMG = $request->input('linkIMG') ?? $noticia->linkIMG;
        } elseif ($request->hasFile('image_file')) {
            // Si se sube un archivo, almacenar la imagen y actualizar el linkIMG
            $path = $request->file('image_file')->store('public/img');
            $noticia->linkIMG = Storage::url($path);
        }

        // Actualizar otros campos
        $noticia->titulo = $request->input('titulo');
        $noticia->texto = $request->input('texto');
        $noticia->save();

        // Actualizar categorías asociadas
        if ($request->has('categorias')) {
            $noticia->categorias()->sync($request->categorias);
        }

        return redirect()->route('dashboard')->with('success', 'Noticia actualizada correctamente.');
    }



    public function categoriasById($id)
    {
        $categorias = DB::table('categoriadenoticia')
            ->join('categorias', 'categoriadenoticia.idCategoria', '=', 'categorias.id')
            ->where('categoriadenoticia.idNoticia', $id)
            ->select('categorias.*')
            ->get();

        return response()->json($categorias);
    }
    public function destroy($id)
    {
        try {
            $noticia = Noticia::findOrFail($id);
            $noticia->categorias()->detach(); // Elimina la relación en la tabla intermedia
            $noticia->delete();
    
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar la noticia'], 500);
        }
    }
    
    public function getItems($id)
    {
        $categoria = Categoria::findOrFail($id);
        $items = $categoria->noticias; // Suponiendo que tienes una relación definida con las noticias en el modelo Categoria

        return response()->json($items);
    }
    public function getNoticiasByCategoria($categoriaId)
    {
        // Obtener las noticias que están asociadas a la categoría específica
        $noticias = Noticia::whereHas('categorias', function ($query) use ($categoriaId) {
            $query->where('categorias.id', $categoriaId); // Asegúrate de que 'categorias.id' es el campo correcto
        })->with('categorias')->get()->map(function ($noticia) {
            return [
                'id' => $noticia->id,
                'titulo' => $noticia->titulo,
                'texto' => $noticia->texto,
                'linkIMG' => $noticia->linkIMG,
                'categorias' => $noticia->categorias->map(function ($categoria) {
                    return [
                        'name' => $categoria->name,
                    ];
                }),
            ];
        });

        return response()->json($noticias);
    }



    public function noticiasByCategoria($id)
    {
        // Obtener las noticias de una categoría específica
        $noticias = Categoria::findOrFail($id)->noticias()->with('categorias')->get()->map(function ($noticia) {
            return [
                'id' => $noticia->id,
                'titulo' => $noticia->titulo,
                'texto' => $noticia->texto,
                'linkIMG' => $noticia->linkIMG,
                'categorias' => $noticia->categorias->map(function ($categoria) {
                    return [
                        'name' => $categoria->name,
                    ];
                }),
            ];
        });

        return response()->json($noticias);
    }
}
