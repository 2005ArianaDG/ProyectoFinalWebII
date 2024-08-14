@extends('index')
@section('newRegistro')
<section class="bg-light py-3 py-md-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                <div class="card border border-light-subtle rounded-3 shadow-sm">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="text-center mb-3">
                        </div>
                        <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Regístrese en su cuenta</h2>
                        <form id="registrationForm">

                            @session('error')
                            <div class="alert alert-danger" role="alert">
                                {{ $value }}
                            </div>
                            @endsession

                            <div class="row gy-2 overflow-hidden">
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="name@example.com" required>
                                        <label for="name" class="form-label">{{ __('Nombre') }}</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="name@example.com" required>
                                        <label for="email" class="form-label">{{ __('Correo electrónico') }}</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" required>
                                        <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="Confirmar contraseña" required>
                                        <label for="password_confirmation" class="form-label">{{ __('Confirmar contraseña') }}</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheck">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Código
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" style="display: none;" id="divCodigo">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control @error('number') is-invalid @enderror" name="codigo" id="codigo_admin" placeholder="Ingrese su Código">
                                        <label for="password_confirmation" class="form-label">{{ __('Código Administrativo') }}</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid my-3">
                                        <div style="display: flex;">
                                            <button class="btn btn-primary" type="submit">{{ __('Registrar') }}</button>
                                            <button style="margin-left: 15px;" class="btn btn-danger" id="btn-cerrar-registrar">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
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
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const form = document.getElementById('registrationForm');
        const check = document.getElementById("flexCheck");
        const codigoAdmin = document.getElementById("codigo_admin");
        const btnCerrar = document.getElementById("btn-cerrar-registrar");

        // Event listener para el botón Cerrar
        btnCerrar.addEventListener("click", function() {
            window.location.href = '/api/noticias/noticias';
        });

        let codigoNum = null;
        let error = '0';
        check.addEventListener("click", function() {
            const div = document.getElementById("divCodigo");
            if (check.checked) {
                div.style.display = '';
                codigoAdmin.required = true; // Hacer que el campo sea obligatorio solo cuando el checkbox esté marcado
                let codigo = document.getElementById("codigo_admin");
                codigo.addEventListener("input", function() {
                    codigoNum = codigo.value;
                    error = '1';
                    console.log(codigoNum);
                });
            } else {
                div.style.display = 'none';
                codigoAdmin.required = false; // Hacer que el campo no sea obligatorio cuando el checkbox no esté marcado
                codigoNum = null;
                error = '0';
            }
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (password.value !== passwordConfirmation.value) {
                alert('Las contraseñas no coinciden.');
                return;
            }

            const formData = new FormData(form);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password'),
                password_confirmation: formData.get('password_confirmation'),
                codigo: codigoNum,
                error: error,
            };

            axios.post('/api/noticias/register', data)
                .then(response => {
                    alert('Usuario registrado exitosamente');
                    window.location.href = '/api/noticias/login'; // Redirigir a la página principal o a la página de login
                })
                .catch(error => {
                    if (error.response && error.response.data) {
                        alert('Error: ' + error.response.data.message);
                    } else {
                        alert('Ocurrió un error inesperado');
                    }
                });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

@endsection