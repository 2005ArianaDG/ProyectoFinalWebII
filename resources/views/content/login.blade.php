@extends('index')
@section('login')
<section class="bg-light py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                <div class="card border border-light-subtle rounded-3 shadow-sm">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="text-center mb-3">
                        </div>
                        <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Iniciar Sesión</h2>
                        <form id="loginForm">
                            <div class="form-group margin-Buttom">
                                <label for="exampleInputEmail1">Correo</label>
                                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Ingrese su correo" required>
                            </div>
                            <div class="form-group margin-Buttom">
                                <label for="exampleInputPassword1">Contraseña</label>
                                <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                            </div>
                            <div style="display: flex;">
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                                <button style="margin-left: 15px;" class="btn btn-danger" id="btn-cerrar-login">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const btnCerrar = document.getElementById("btn-cerrar-login");

        // Event listener para el botón Cerrar
        btnCerrar.addEventListener("click", function() {
            console.log("Cerrando ventana de registro");
            window.location.href = '/api/noticias/noticias';
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            axios.post('/api/noticias/loginSession', {
                    email: email,
                    password: password
                })
                .then(function(response) {
                    // Manejo de la respuesta exitosa
                    console.log(response.data);
                    if (response.data.message === 'Inicio de sesión exitoso ad' && response.data.number === 1) {
                        alert('Inicio de sesión exitoso Administrador');
                        // Redirigir a la página principal
                        window.location.href = '/admin2';
                    } else if (response.data.message === 'Inicio de sesión exitoso n' && response.data.number === 2) {
                        window.location.href = '/';
                        alert('Inicio de sesión exitoso');
                    } else if (response.data.message === 'Contraseña inválida') {
                        alert('Contraseña Incorrecta');
                    } else if (response.data.message === 'El correo no existe') {
                        alert('El correo no existe');
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                })
                .catch(function(error) {
                    // Manejo del error
                    console.error(error);
                    alert('Error al intentar iniciar sesión' + error.message);
                });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
@endsection