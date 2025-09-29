@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@push('styles')
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #212529; /* Color de fondo oscuro para que las partículas resalten */
        }
        /* Contenedor para el fondo animado */
        #tsparticles {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1; /* MUY IMPORTANTE: Lo pone detrás de todo */
        }
        .login-container {
            position: relative; /* Para que esté por encima del fondo */
            z-index: 1;
            max-width: 400px;
            margin: 10vh auto;
            background: rgba(255, 255, 255, 0.95); /* Fondo de la tarjeta ligeramente transparente */
            border-radius: 15px;
        }
        .card-header-custom {
            background-color: #212529;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
    </style>
@endpush

@section('content')
    <!-- Contenedor para el fondo de partículas -->
    <div id="tsparticles"></div>

    <div class="container">
        <div class="login-container">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center card-header-custom p-4">
                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" style="width: 80px; border-radius: 50%; margin-bottom: 1rem; border: 2px solid #dc3545;">
                    <h4>INFINITY GYM CENTER</h4>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title text-center mb-4">Iniciar Sesión</h5>
                    <form id="form-login" method="POST" action="{{ route('login') }}">
                        @csrf {{-- Token de seguridad de Laravel --}}
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Contraseña</label>
                             <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control" id="clave" name="clave" required>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-danger btn-block">Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <!-- CDN de la librería tsParticles -->
    <script src="https://cdn.jsdelivr.net/npm/tsparticles-slim@2.12.0/tsparticles.slim.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Lógica del formulario de login (sin cambios)
            $('#form-login').submit(function(e) { // Este submit ahora es para AJAX, no para el form normal
                e.preventDefault();
                const formData = $(this).serialize();

                // Apuntamos a la API de login que ya creamos
                $.post("{{ url('/api/login') }}", formData, function(response) {
                    if (response.success) {
                        // Redirigimos a una ruta principal de Laravel (ej. /dashboard)
                        // Guardamos el rol del usuario para usarlo en el frontend
                        localStorage.setItem('userRole', response.user.rol);
                        window.location.href = "{{ route('dashboard') }}";
                    } else {
                        // El 'else' puede que no se use si Laravel siempre devuelve error 4xx
                        Swal.fire('Error', response.message, 'error');
                    }
                }, 'json').fail(function(jqXHR) {
                    // Manejo de errores de validación de Laravel (código 422) u otros.
                    let message = 'Ocurrió un error inesperado. Por favor, intente de nuevo.'; // Mensaje por defecto

                    if (jqXHR.status === 422) {
                        // Error de validación de Laravel
                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            message = Object.values(jqXHR.responseJSON.errors).flat().join('\n');
                        } else {
                            message = 'Usuario o clave incorrectos.';
                        }
                    } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        // Otros errores JSON con un campo de mensaje
                        message = jqXHR.responseJSON.message;
                    } else if (jqXHR.responseText) {
                        // Si no es JSON, podría ser un error de PHP. Mostrar en consola para depurar.
                        console.error('Error del servidor:', jqXHR.responseText);
                    }

                    Swal.fire('Error', message, 'error');
                });
            });

            // Configuración del fondo animado
            tsParticles.load("tsparticles", {
                background: {
                    color: {
                        value: "#212529" // Coincide con el color del body y el header
                    }
                },
                fpsLimit: 60,
                interactivity: {
                    events: {
                        onHover: {
                            enable: true,
                            mode: "repulse" // Las partículas se alejan del cursor
                        }
                    },
                    modes: {
                        repulse: {
                            distance: 100,
                            duration: 0.4
                        }
                    }
                },
                particles: {
                    color: {
                        value: "#dc3545" // Color rojo de tu marca
                    },
                    links: {
                        color: "#dc3545",
                        distance: 150,
                        enable: true,
                        opacity: 0.2, // Líneas sutiles
                        width: 1
                    },
                    move: {
                        direction: "none",
                        enable: true,
                        outModes: {
                            default: "bounce"
                        },
                        random: false,
                        speed: 1.5, // Velocidad lenta y elegante
                        straight: false
                    },
                    number: {
                        density: {
                            enable: true,
                            area: 800
                        },
                        value: 80 // Cantidad de partículas
                    },
                    opacity: {
                        value: 0.3 // Partículas sutiles
                    },
                    shape: {
                        type: "circle"
                    },
                    size: {
                        value: { min: 1, max: 4 }
                    }
                },
                detectRetina: true
            });
        });
    </script>
@endpush