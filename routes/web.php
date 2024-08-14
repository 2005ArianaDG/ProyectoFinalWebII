<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerNoticias;
use App\Http\Controllers\CategoriaController;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\Noticia;
use App\Models\Categoria;
// Ruta principal

Route::get('/', function () {
    $noticias = Noticia::all();
    $categorias = Categoria::all(); // Obtén las categorías
    return view('home', compact('noticias', 'categorias'));
})->name('home');


// Ruta de autenticación con `AuthController`
Route::get('home', [AuthController::class, 'homepage'])->name('homepage');
Route::get('login', [AuthController::class, 'login'])->name('login'); // Muestra el formulario de login
Route::post('login', [AuthController::class, 'postLogin'])->name('login.post'); // Procesa el formulario de login
Route::get('AdminHome', [ControllerNoticias::class, 'index2'])->name('AdminHome');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/noticias/categoria/{id}', [ControllerNoticias::class, 'getNoticiasByCategoria']);
Route::get('/noticias2/{id}', [ControllerNoticias::class, 'getNoticiaById']);
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [CategoriaController::class, 'index'])->name('dashboard');

    Route::get('/noticias/create', [ControllerNoticias::class, 'create'])->name('noticias.create');
    Route::get('/noticiasAll', [ControllerNoticias::class, 'getNoticias']);
    Route::get('/categoriaAll', [ControllerNoticias::class, 'AllCategorias']);
    Route::post('/noticias2', [ControllerNoticias::class, 'store'])->name('noticias.store');
    Route::post('/noticias/{id}', [ControllerNoticias::class, 'update'])->name('noticias.update');
    Route::get('/datos/{id}', [ControllerNoticias::class, 'categoriasById']);
    // Ruta para eliminar una noticia
    Route::delete('/noticias/{id}', [ControllerNoticias::class, 'destroy'])->name('noticias.destroy');

    Route::get('/categorias/{id}/items', [ControllerNoticias::class, 'getItems'])->name('noticias.items');
    Route::get('/noticias', [ControllerNoticias::class, 'getNoticias']);
    Route::get('/categorias/{id}/noticias', [ControllerNoticias::class, 'noticiasByCategoria']);

    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::resource('categorias', CategoriaController::class);
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::get('/categorias/{id}/delete', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::get('/categoria/{id}/items', [CategoriaController::class, 'showItems'])->name('categoria.items');

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Registro del middleware de rol
Route::aliasMiddleware('role', EnsureUserHasRole::class);

// Formularios y funciones de recuperación de contraseña
Route::get('/formulario-recuperar-contrasenia', [AuthController::class, 'formularioRecuperarContrasenia'])->name('formulario-recuperacion-contrasenia');
Route::post('/enviar-recuperar-contrasenia', [AuthController::class, 'enviarRecuperarContrasenia'])->name('enviar-recuperacion');
Route::get('/reiniciar-contrasenia/{token}', [AuthController::class, 'formularioActualizacion'])->name('formulario-actualizar-contrasenia');
Route::post('/actualizar-contrasenia', [AuthController::class, 'actualizarContrasenia'])->name('actualizar-contrasenia');
