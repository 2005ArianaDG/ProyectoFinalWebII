<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        /* Estilos CSS adicionales */
        .carousel-control-prev,
        .carousel-control-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: none;
            width: 50px;
            height: 50px;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 40px;
            height: 40px;
            background-color: transparent;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(49, 49, 49, 0.5);
            border-radius: 50%;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
        }

        .select2-container--open {
            z-index: 9999;
        }

        .btn2 {
            color: #0d6efd;
            border: 1px solid #0d6efd;
            background-color: transparent;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.2rem;
            margin-right: 0.5rem !important;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        .btn2:hover {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn2.disabled,
        .btn2:disabled {
            color: #0d6efd;
            background-color: transparent;
            border-color: #0d6efd;
            cursor: not-allowed;
            opacity: 0.65;
        }

        .header-buttons .btn.custom-btn {
            background: linear-gradient(to right, #C8E6C9, #BBDEFB);
            /* Verde */
            border: 2px solid #4CAF50;
            color: #4CAF50;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }


        .header-buttons .btn.custom-btn:focus,
        .header-buttons .btn.custom-btn:active {
            background-color: #45a049;
            /* Verde oscuro */
            color: white;
            border-color: #45a049;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }
    </style>

    <x-slot name="header">
        <div class="header-buttons d-flex justify-content-start align-items-center gap-4">
            <!-- Botón para añadir noticia -->
            <button id="add" type="button" class="btn custom-btn" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                Añadir Noticia
            </button>
        </div>
    </x-slot>

    <section id="services">
        <div class="container mt-1">
            <h2 class="text-center">Noticias</h2>
            <div id="carouselServices" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner servicios">

                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselServices" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselServices" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </section>
    <br>
    <div class="header-buttons d-flex justify-content-start align-items-center gap-4">
        <!-- Botón para añadir categoría -->
        <button type="button" class="btn custom-btn btnCate" data-bs-toggle="modal" data-bs-target="#categoriaModal">
            Añadir Categoría
        </button>
    </div>
    <div>
        <h2 class="text-center">Categorias</h2>
        <div class="container">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-white">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->name }}</td>
                        <td>{{ $categoria->descripcion }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" data-id="{{ $categoria->id }}" data-name="{{ $categoria->name }}" data-descripcion="{{ $categoria->descripcion }}" data-bs-toggle="modal" data-bs-target="#categoriaModal" onclick="editarCategoria(this)">Editar</button>
                                <form action="{{ route('categorias.destroy', ['id' => $categoria->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" data-id="{{ $categoria->id }}" data-type="categoria">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- Modal ver más -->
    <div class="modal fade" id="modalVerMas" tabindex="-1" aria-labelledby="modalVerMasLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalVerMasLabel">Detalles de la Noticia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="modalTitulo" class="mb-3"></h4>
                    <p id="modalTexto" class="mb-4"></p>
                    <img id="modalImagen" class="img-fluid rounded-3" src="" alt="Imagen de la noticia" style="display: none;">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Nueva Noticia</h5>
                    <button id="cerrarIcon" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data" id="formEditAdd">
                        @csrf
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="texto" class="form-label">Texto:</label>
                            <textarea class="form-control" id="texto" name="texto" required></textarea>
                        </div>

                        <select id="Categoria" class="form-control select2" name="categorias[]" multiple="multiple" style="width: 300px; border: 1px solid #dee2e6">
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                            @endforeach
                        </select>

                        <div class="mb-3">
                            <label class="form-label">Opción de Imagen:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="image_option" id="url_option" value="url" checked>
                                <label class="form-check-label" for="url_option">URL de la imagen</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="image_option" id="upload_option" value="upload">
                                <label class="form-check-label" for="upload_option">Subir imagen</label>
                            </div>
                        </div>
                        <div class="mb-3" id="url_input">
                            <label for="linkIMG" class="form-label">URL de la imagen:</label>
                            <input type="text" class="form-control" id="linkIMG" name="linkIMG" required>
                        </div>
                        <div class="mb-3" id="upload_input" style="display:none;">
                            <label for="image_file" class="form-label">Subir imagen:</label>
                            <input class="form-control" type="file" id="image_file" name="image_file">
                        </div>
                        <div id="image_preview">
                            <img id="preview_img" src="" alt="Vista previa de la imagen" style="display:none; width: 250px; height: 250px;">
                        </div>
                        <div class="modal-footer">
                            <button id="cerrar" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoriaModalLabel">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categorias.store') }}" id="categoriaForm" method="POST" enctype="multipart/form-data">
                        <!-- <form id="categoriaForm"> -->
                        @csrf
                        <div class="mb-3">
                            <label for="nombreCategoria" class="form-label">Nombre de la Categoría:</label>
                            <input type="text" class="form-control" id="nombreCategoria" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcionCategoria" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="descripcionCategoria" name="descripcion"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer py-5 shadow-sm mt-5">
        <div class="container">
            <div class="text-center mt-4">
                <p>&copy; 2024 Noticias. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('/js/main.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                tags: true
            });
        });

        let num = 0;
        let categoriasSeleccionadas = [];

        let btn = document.getElementById("add");
        btn.addEventListener("click", function() {
            document.getElementById('formEditAdd').action = "{{ route('noticias.store') }}";
            document.getElementById("Categoria").innerHTML = ``;
            axios.get(`/categoriaAll`)
                .then(function(response) {
                    const data1 = response.data;
                    const categoriaElement = document.getElementById("Categoria");
                    data1.forEach((item1) => {
                        categoriaElement.innerHTML += `
                            <option value="${item1.id}">${item1.name}</option>
                        `;
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });

            document.getElementById('preview_img').style.display = 'none';
            document.getElementById('image_preview').style.display = 'none';
            document.getElementById('image_preview').src = null;
            document.getElementById("url_option").checked = true;
            document.getElementById('titulo').value = null;
            document.getElementById('texto').textContent = null;
            $('#url_input').show();
            $('#upload_input').hide();
            $('#image_preview').hide();
            $('#linkIMG').attr('required', false);
            $('#image_file').attr('required', false);
            $('#preview_img').attr('required', false);
        });

        let btnCerrarIcon = document.getElementById("cerrarIcon");
        btnCerrarIcon.addEventListener("click", function() {
            document.getElementById('Categoria').innerHTML = ``;
        });

        let btnCerrar = document.getElementById("cerrar");
        btnCerrar.addEventListener("click", function() {
            document.getElementById('Categoria').innerHTML = ``;
        });

        $(document).ready(function() {
            // Manejo del formulario de edición/agregado de noticia
            $('#formEditAdd').on('submit', function(event) {
                event.preventDefault(); // Evita el envío del formulario por defecto

                // Muestra SweetAlert de confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Quieres guardar los cambios en esta noticia?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si se confirma, envía el formulario
                        this.submit();
                    }
                });
            });

            $('input[name="image_option"]').change(function() {
                if ($(this).val() == 'url') {
                    $('#url_input').show();
                    $('#upload_input').hide();
                    $('#image_preview').hide();
                    $('#linkIMG').attr('required', true);
                    $('#image_file').attr('required', false);
                    $('#preview_img').attr('required', false);
                    document.getElementById("preview_img").style.display = 'block';
                } else {
                    $('#url_input').hide();
                    $('#upload_input').show();
                    $('#linkIMG').attr('required', false);
                    $('#image_file').attr('required', true);
                }
            });

            axios.get('/noticiasAll')
                .then(function(response) {
                    const noticias = response.data;
                    const noticiasContainer = document.querySelector('#carouselServices .carousel-inner');

                    let carouselItem = document.createElement('div');
                    carouselItem.className = 'carousel-item active';

                    let row = document.createElement('div');
                    row.className = 'row';

                    noticias.forEach((noticia, index) => {
                        if (index > 0 && index % 4 === 0) {
                            carouselItem.appendChild(row);
                            noticiasContainer.appendChild(carouselItem);
                            carouselItem = document.createElement('div');
                            carouselItem.className = 'carousel-item';
                            row = document.createElement('div');
                            row.className = 'row';
                        }

                        const col = document.createElement('div');
                        col.className = 'col-md-3';

                        const divServis = document.createElement('div');
                        divServis.className = 'bg-white shadow div-servis2 mb-4';

                        divServis.innerHTML = `
                            <div class="img-container">
                                <img src="${noticia.linkIMG}" class="img-fluid" alt="Imagen de ${noticia.titulo}">
                            </div>
                            <div class="titlecontainer2">
                                <h3 class="noticiaTitulo" style="cursor: pointer; text-decoration: underline;" data-id="${noticia.id}">${noticia.titulo}</h3>
                            </div>
                            <div class="btn-container d-flex justify-content-center mt-2">
                                <button class="btn btn-outline-primary btn-sm me-2" data-id="${noticia.id}">Editar</button>
                                <button class="btn btn-outline-danger btn-sm delete-btn" data-id="${noticia.id}">Eliminar</button>
                            </div>
                        `;

                        col.appendChild(divServis);
                        row.appendChild(col);
                    });

                    carouselItem.appendChild(row);
                    noticiasContainer.appendChild(carouselItem);

                    // Manejo del modal de "Ver Más"
                    $(document).on('click', '.noticiaTitulo', function() {
                        const noticiaId = $(this).data('id');
                        mostrarModalVerMas(noticiaId);
                    });

                    let botones = document.getElementsByClassName("me-2");
                    Array.from(botones).forEach(function(btn) {
                        btn.addEventListener("click", function() {
                            const noticiaId = btn.getAttribute('data-id');
                            const modal = new bootstrap.Modal(document.getElementById('exampleModalCenter'));
                            document.getElementById('formEditAdd').action = `/noticias/${noticiaId}`; // Aquí no necesitas Blade
                            axios.get(`/noticias2/${noticiaId}`)
                                .then(function(response) {
                                    const data = response.data; // Suponiendo que la respuesta contiene los datos de la noticia
                                    document.getElementById("titulo").value = data.titulo;
                                    document.getElementById("texto").textContent = data.texto; // Corregir aquí para usar "data.texto"
                                    if (data.linkIMG.substring(0, 4) === 'http') {
                                        document.getElementById("preview_img").src = data.linkIMG;
                                        document.getElementById("url_option").checked = true;
                                        $('#url_input').show();
                                        $('#upload_input').hide();
                                        $('#image_preview').hide();
                                        $('#linkIMG').attr('required', false);
                                        $('#image_file').attr('required', false);
                                        $('#preview_img').attr('required', false);
                                        document.getElementById('preview_img').style.display = 'block';
                                        document.getElementById('image_preview').style.display = 'block';
                                    } else {
                                        document.getElementById("preview_img").src = data.linkIMG;
                                        $('#url_input').hide();
                                        $('#upload_input').show();
                                        $('#linkIMG').attr('required', false);
                                        $('#image_file').attr('required', false);
                                        document.getElementById("upload_option").checked = true;
                                        document.getElementById('preview_img').style.display = 'block';
                                        document.getElementById('image_preview').style.display = 'block';
                                    }
                                })
                                .catch(function(error) {
                                    console.log(error);
                                });

                            let data1;
                            axios.get(`/categoriaAll`)
                                .then(function(response) {
                                    data1 = response.data; // Datos de todas las categorías
                                    axios.get(`/datos/${noticiaId}`)
                                        .then(function(response) {
                                            const data2 = response.data; // Categorías de la noticia específica
                                            document.getElementById("Categoria").innerHTML = ``;
                                            data1.forEach((item1) => {
                                                const exists = data2.some(item2 => item2.id === item1.id);

                                                if (exists) {
                                                    document.getElementById("Categoria").innerHTML += `
                                                        <option value="${item1.id}" selected>${item1.name}</option>
                                                    `;
                                                } else {
                                                    document.getElementById("Categoria").innerHTML += `
                                                        <option value="${item1.id}">${item1.name}</option>
                                                    `;
                                                }
                                            });
                                        })
                                        .catch(function(error) {
                                            console.log(error);
                                        });

                                })
                                .catch(function(error) {
                                    console.log(error);
                                });

                            modal.show();
                        });
                    });

                    document.querySelectorAll('.delete-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const noticiaId = button.getAttribute('data-id');

                            Swal.fire({
                                title: '¿Estás seguro?',
                                text: "¡Esta noticia será eliminada!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Sí, eliminar!',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Realizar la eliminación a través de una petición DELETE
                                    axios.delete(`/noticias/${noticiaId}`)
                                        .then(response => {
                                            Swal.fire(
                                                'Eliminado!',
                                                'La noticia ha sido eliminada.',
                                                'success'
                                            ).then(() => {
                                                window.location.reload(); // Opcional: recargar la página
                                            });
                                        })
                                        .catch(error => {
                                            console.error(error); // Verifica el error en la consola
                                            Swal.fire(
                                                'Error!',
                                                'No se pudo eliminar la noticia.',
                                                'error'
                                            );
                                        });
                                }
                            });
                        });
                    });
                    botones = document.getElementsByClassName("btn-outline-danger");
                    Array.from(botones).forEach(function(btn) {
                        btn.addEventListener("click", function(e) {
                            e.preventDefault(); // Evita la acción predeterminada del botón
                            const id = btn.getAttribute('data-id'); // Obtén el ID desde el atributo data-id
                            const type = btn.getAttribute('data-type'); // Obtén el tipo de acción desde el atributo data-type

                            let url;
                            let confirmationText;

                            if (type === 'categoria') {
                                url = `/categorias/${id}/delete`; // URL para eliminar la categoría
                                confirmationText = "¡Esta categoría y todas las noticias asociadas serán eliminadas!";
                            } else if (type === 'noticia') {
                                url = `/noticias/${id}/delete`; // URL para eliminar la noticia
                                confirmationText = "¡Esta noticia será eliminada!";
                            } else {
                                return; // Salir si el tipo no es reconocido
                            }

                            Swal.fire({
                                title: '¿Estás seguro?',
                                text: confirmationText,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Sí, eliminar!',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirige a la URL para eliminar según el tipo
                                    window.location.href = url;
                                }
                            });
                        });
                    });

                })
                .catch(function(error) {
                    console.log(error);
                });

            // Función para mostrar el modal de "Ver Más"
            function mostrarModalVerMas(id) {
                axios.get(`/noticias2/${id}`)
                    .then(function(response) {
                        const noticia = response.data;
                        $('#modalTitulo').text(noticia.titulo);
                        $('#modalTexto').text(noticia.texto);
                        $('#modalImagen').attr('src', noticia.linkIMG).show();

                        const modalVerMas = new bootstrap.Modal(document.getElementById('modalVerMas'));
                        modalVerMas.show();
                    })
                    .catch(function(error) {
                        console.error('Error al obtener los datos de la noticia:', error);
                    });
            }

        });

        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="image_option"]');

            // Añade un listener a cada radio button
            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    console.log(`Opción seleccionada: ${this.value}`);
                    if (this.value === 'url') {
                        document.getElementById("image_file").value = null;
                        document.getElementById('preview_img').src = null;
                        document.getElementById('preview_img').style.display = 'none';
                    } else {
                        document.getElementById("linkIMG").value = null;
                        document.getElementById('preview_img').src = null;
                        document.getElementById('preview_img').style.display = 'none';
                    }
                });
            });
        });

        function editarCategoria(button) {
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var descripcion = button.getAttribute('data-descripcion');

            document.getElementById('nombreCategoria').value = name;
            document.getElementById('descripcionCategoria').value = descripcion;

            document.getElementById('categoriaModalLabel').innerText = 'Editar Categoría';

            var form = document.getElementById('categoriaForm');
            form.action = '/categorias/' + id;
            form.method = 'POST';

            // Añadir el método PUT usando un campo oculto
            var methodField = document.createElement('input');
            methodField.setAttribute('type', 'hidden');
            methodField.setAttribute('name', '_method');
            methodField.setAttribute('value', 'PUT');
            form.appendChild(methodField);
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview_img');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        document.getElementById('image_file').addEventListener('change', function(event) {
            previewImage(event);
            console.log("hola2");
            document.getElementById('preview_img').style.display = 'block';
            document.getElementById('image_preview').style.display = 'block';
        });

        document.getElementById("linkIMG").addEventListener('change', function(event) {
            console.log("hola1");
            document.getElementById('preview_img').src = this.value;
            console.log(this.value);
            document.getElementById('image_preview').style.display = 'block';
            document.getElementById('preview_img').style.display = 'block';
        });
    </script>
</x-app-layout>