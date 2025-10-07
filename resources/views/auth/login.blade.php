<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Infinity Gym Center - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <style>
        body { 
            background-color: #212529;
        }
        #tsparticles {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .login-container { 
            position: relative;
            z-index: 1;
            max-width: 400px; 
            margin: 10vh auto; 
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
        }
        .card-header-custom { 
            background-color: #212529; 
            color: white; 
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
    </style>
</head>
<body>
    <div id="tsparticles"></div>

    <div class="container">
        <div class="login-container">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center card-header-custom p-4">
                    <h4>INFINITY GYM CENTER</h4>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title text-center mb-4">Iniciar Sesión</h5>
                    <form id="form-login">
                        @csrf
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles-slim@2.12.0/tsparticles.slim.bundle.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#form-login').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.post('/api/auth/login', formData, function(response) {
                    if (response.success) {
                        window.location.href = '/';
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }, 'json').fail(() => Swal.fire('Error de Red', 'No se pudo conectar.', 'error'));
            });

            tsParticles.load("tsparticles", {
                background: {
                    color: { value: "#212529" }
                },
                fpsLimit: 60,
                interactivity: {
                    events: {
                        onHover: {
                            enable: true,
                            mode: "repulse"
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
                    color: { value: "#dc3545" },
                    links: {
                        color: "#dc3545",
                        distance: 150,
                        enable: true,
                        opacity: 0.2,
                        width: 1
                    },
                    move: {
                        direction: "none",
                        enable: true,
                        outModes: { default: "bounce" },
                        random: false,
                        speed: 1.5,
                        straight: false
                    },
                    number: {
                        density: { enable: true, area: 800 },
                        value: 80
                    },
                    opacity: { value: 0.3 },
                    shape: { type: "circle" },
                    size: { value: { min: 1, max: 4 } }
                },
                detectRetina: true
            });
        });
    </script>
</body>
</html>
