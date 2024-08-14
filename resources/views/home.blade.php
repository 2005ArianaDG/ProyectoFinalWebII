<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link href="{{ asset('css/styleHome.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>

<body class="custom-bg">
    <!-- Header -->
    <header class="header py-3 shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <a href="/" class="text-decoration-none text-black">
                        <h1 class="h3 mb-0">Noticias</h1>
                    </a>

                </div>
                <div class="col-lg-5 text-end">
                    <nav class="nav justify-content-end">
                        <ul class="nav">
                            @auth
                            <li class="nav-item">
                                <a class="nav-link" href="/profile">Cuenta</a>
                            </li>
                            @if (Auth::user()->admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('AdminHome') }}">Administración</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link" id="logoutBtn">Cerrar Sesión</button>
                                </form>
                            </li>
                            @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Inicio Sesión</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                            </li>
                            @endauth
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </header>

    <div class="container mt-3">
        <div class="mb-3">
            <select id="categoriaSelect" class="form-select form-select-lg mb-3" aria-label="Seleccione una categoría">
                <option value="" selected disabled>Seleccione una categoría</option>
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Carousel de noticias -->
        <section id="services">
            <div class="container mt-3">
                <h2 class="text-center">Noticias</h2>
                <div id="carouselServices" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner servicios" id="carouselInner">
                        @foreach ($noticias->chunk(4) as $index => $chunk)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }} mt-3">
                            <div class="row">
                                @foreach ($chunk as $noticia)
                                <div class="col-md-3">
                                    <div class="bg-white shadow div-servis mb-3">
                                        <div class="img-container">
                                            <img src="{{ $noticia->linkIMG }}" class="img-fluid" alt="Imagen de {{ $noticia->titulo }}">
                                        </div>
                                        <div class="titlecontainer">
                                            <h3 class="noticiaTitulo" data-id="{{ $noticia->id }}" style="cursor: pointer; text-decoration: underline;">{{ $noticia->titulo }}</h3>
                                            @foreach ($noticia->categorias as $categoria)
                                            <p class="info-categorias mt-5">{{ $categoria->name }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
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
            </div>
        </section>
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
                    <h4 id="modalTitulo" class="mb-3">Título:</h4>
                    <p id="modalCategoria" class="text-muted mb-2">Categorías: </p>
                    <p id="modalTexto" class="mb-2">Descripción</p>
                    <img id="modalImagen" class="img-fluid rounded-3" src="" alt="Imagen de la noticia" style="display: none;">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer py-5 shadow-sm mt-3">
        <div class="container">
            <div class="text-center mt-5">
                <p>&copy; 2024 Noticias. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="{{ asset('/js/main.js') }}"></script>
    <!-- Bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- jQuery (required for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.noticiaTitulo', function() {
                const noticiaId = $(this).data('id');
                mostrarModalVerMas(noticiaId);
            });

            function mostrarModalVerMas(id) {
                axios.get(`/noticias2/${id}`)
                    .then(function(response) {
                        const noticia = response.data;
                        console.log(noticia); // Imprime la respuesta en la consola para depuración

                        $('#modalTitulo').text(`Título: ${noticia.titulo}`);
                        $('#modalTexto').text(`${noticia.texto}`);
                        $('#modalImagen').attr('src', noticia.linkIMG).show();

                        // Mostrar categorías
                        const categorias = noticia.categorias.map(c => c.name).join(', ');
                        $('#modalCategoria').text(`Categoría: ${categorias || 'No disponible'}`);

                        const modalVerMas = new bootstrap.Modal(document.getElementById('modalVerMas'));
                        modalVerMas.show();
                    })
                    .catch(function(error) {
                        console.error('Error al obtener los datos de la noticia:', error);
                    });
            }
            document.getElementById('categoriaSelect').addEventListener('change', function() {
                const categoriaId = this.value;
                const carouselInner = document.getElementById('carouselInner');
                carouselInner.innerHTML = '';

                if (categoriaId) {
                    fetch(`/noticias/categoria/${categoriaId}`)
                        .then(response => response.json())
                        .then(noticias => {
                            if (noticias.length === 0) {
                                const noNoticiasMessage = document.createElement('div');
                                noNoticiasMessage.className = 'alert alert-info';
                                noNoticiasMessage.textContent = 'No hay noticias para esta categoría.';
                                carouselInner.appendChild(noNoticiasMessage);
                            } else {
                                let chunkedNoticias = [];
                                for (let i = 0; i < noticias.length; i += 4) {
                                    chunkedNoticias.push(noticias.slice(i, i + 4));
                                }

                                chunkedNoticias.forEach((chunk, index) => {
                                    const carouselItem = document.createElement('div');
                                    carouselItem.className = `carousel-item ${index === 0 ? 'active' : ''} mt-5`;

                                    const row = document.createElement('div');
                                    row.className = 'row';

                                    chunk.forEach(noticia => {
                                        const col = document.createElement('div');
                                        col.className = 'col-md-3';

                                        const divServis = document.createElement('div');
                                        divServis.className = 'bg-white shadow div-servis mb-5';

                                        const imgContainer = document.createElement('div');
                                        imgContainer.className = 'img-container';

                                        const img = document.createElement('img');
                                        img.src = noticia.linkIMG;
                                        img.alt = `Imagen de ${noticia.titulo}`;
                                        img.className = 'img-fluid';

                                        imgContainer.appendChild(img);

                                        const titleContainer = document.createElement('div');
                                        titleContainer.className = 'titlecontainer';

                                        const h3 = document.createElement('h3');
                                        h3.className = 'noticiaTitulo';
                                        h3.dataset.id = noticia.id;
                                        h3.style.cursor = 'pointer';
                                        h3.style.textDecoration = 'underline';
                                        h3.textContent = noticia.titulo;


                                        titleContainer.appendChild(h3);


                                        // Mostrar categorías
                                        if (noticia.categorias && Array.isArray(noticia.categorias)) {
                                            noticia.categorias.forEach(categoria => {
                                                const pCategoria = document.createElement('p');
                                                pCategoria.className = 'info-categorias mt-3';
                                                pCategoria.textContent = categoria.name;
                                                titleContainer.appendChild(pCategoria);
                                            });
                                        } else {
                                            console.warn('No se encontraron categorías para la noticia:', noticia.id);
                                        }

                                        divServis.appendChild(imgContainer);
                                        divServis.appendChild(titleContainer);

                                        col.appendChild(divServis);
                                        row.appendChild(col);
                                    });

                                    carouselItem.appendChild(row);
                                    carouselInner.appendChild(carouselItem);
                                });
                            }
                        });
                } else {
                    // Si no hay categoría seleccionada, cargar todas las noticias
                    fetch('/noticias')
                        .then(response => response.json())
                        .then(data => {
                            updateCarousel(data);
                        });
                }

                function updateCarousel(noticias) {
                    const carouselInner = document.getElementById('carouselInner');
                    carouselInner.innerHTML = '';

                    if (noticias.length === 0) {
                        carouselInner.innerHTML = '<p>No hay noticias para mostrar.</p>';
                        return;
                    }

                    let chunks = [];
                    while (noticias.length) {
                        chunks.push(noticias.splice(0, 4));
                    }

                    chunks.forEach((chunk, index) => {
                        const carouselItem = document.createElement('div');
                        carouselItem.className = `carousel-item ${index === 0 ? 'active' : ''} mt-5`;
                        const row = document.createElement('div');
                        row.className = 'row';

                        chunk.forEach(noticia => {
                            const col = document.createElement('div');
                            col.className = 'col-md-3';
                            col.innerHTML = `
                            <div class="bg-white shadow div-servis mb-5">
                                <div class="img-container">
                                    <img src="${noticia.linkIMG}" class="img-fluid" alt="Imagen de ${noticia.titulo}">
                                </div>
                                <div class="titlecontainer">
                                    <h3 class="noticiaTitulo" data-id="${noticia.id}" style="cursor: pointer; text-decoration: underline;">${noticia.titulo}</h3>
                                
                                    ${noticia.categorias && Array.isArray(noticia.categorias) ? noticia.categorias.map(cat => `<p class="info-categorias">${cat.name}</p>`).join('') : ''}
                                </div>
                            </div>
                        `;
                            row.appendChild(col);
                        });

                        carouselItem.appendChild(row);
                        carouselInner.appendChild(carouselItem);
                    });
                }
            });
        });
    </script>

</body>

</html>