<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpg') }}">
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <script>
        // Pasamos el rol del usuario autenticado a JavaScript
        const USER_ROLE = '{{ Auth::user()->rol }}';
        // Definimos URLs base para los assets públicos
        const defaultImageUrl = "{{ asset('uploads/default.png') }}";
        const dataTablesLanguageUrl = "{{ asset('js/i18n/es-ES.json') }}";
    </script>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
          <img src="{{ asset('img/logo.jpg') }}" alt="Infinity Gym Logo" class="navbar-logo">
          <span class="fw-bold">INFINITY GYM CENTER</span>
        </a>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" id="nav-link-sistema" href="#" role="button">Sistema</a>
                </li>
                @if (Auth::user()->rol === 'admin')
                <li class="nav-item">
                    <a class="nav-link" id="nav-link-admin" href="#" role="button">Admin Settings</a>
                </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-circle me-1"></i> {{ Auth::user()->nombre }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main id="contenido-sistema">
        @yield('content')
    </main>

    <main class="container mt-4" id="contenido-admin" style="display: none;">
        <h2 class="mb-3"><i class="fa-solid fa-users-cog me-2"></i>Administración de Usuarios</h2>
        <button class="btn btn-danger mb-3" id="btn-nuevo-usuario"><i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Usuario</button>
        <div id="lista-usuarios-container"></div>
    </main>

    {{-- Incluimos los modales como una vista parcial de Blade --}}
    @include('partials.modales')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    {{-- Usamos asset() para todos los scripts locales --}}
    <script>
        // Configuración global de AJAX para incluir el token CSRF de Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/recepcion.js') }}"></script>
    <script src="{{ asset('js/miembros.js') }}"></script>
    <script src="{{ asset('js/inventario.js') }}"></script>
    <script src="{{ asset('js/entrenadores.js') }}"></script>
    <script src="{{ asset('js/suscripciones.js') }}"></script>
    <script src="{{ asset('js/usuarios.js') }}"></script>
</body>
</html>