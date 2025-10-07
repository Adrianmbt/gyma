<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Infinity Gym Center - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .navbar-logo { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .btn-pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
</head>
<body>
    <script>
        const USER_ROLE = '{{ session("usuario_rol") }}';
    </script>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <span class="fw-bold">INFINITY GYM CENTER</span>
        </a>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" id="nav-link-sistema" href="#" role="button">Sistema</a>
                </li>
                @if(session('usuario_rol') === 'admin')
                <li class="nav-item">
                    <a class="nav-link" id="nav-link-admin" href="#" role="button">Admin Settings</a>
                </li>
                @endif
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-circle me-1"></i> {{ session('usuario_nombre') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" id="btn-logout">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
      </div>
    </nav>

    <main class="container mt-4" id="contenido-sistema">
        <!-- Pestañas de Navegación -->
        <ul class="nav nav-tabs justify-content-center mb-4" id="mainTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="recepcion-tab" data-bs-toggle="tab" data-bs-target="#recepcion" type="button" role="tab">Recepción y Ventas</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="miembros-tab" data-bs-toggle="tab" data-bs-target="#miembros" type="button" role="tab">Gestión de Miembros</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="suscripciones-tab" data-bs-toggle="tab" data-bs-target="#suscripciones" type="button" role="tab">Suscripciones y Promociones</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="entrenadores-tab" data-bs-toggle="tab" data-bs-target="#entrenadores" type="button" role="tab">Gestión de Entrenadores</button>
          </li>
        
          @if(in_array(session('usuario_rol'), ['admin', 'supervisor']))
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="inventario-tab" data-bs-toggle="tab" data-bs-target="#inventario" type="button" role="tab">Gestión de Inventario</button>
            </li>
          @endif
        
          @if(session('usuario_rol') === 'admin')
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="analisis-tab" data-bs-toggle="tab" data-bs-target="#analisis" type="button" role="tab">Análisis y Reportes</button>
            </li>
          @endif
        </ul>

        <!-- Contenido de las Pestañas -->
        <div class="tab-content" id="mainTabsContent">
            <!-- Pestaña: Recepción y Ventas -->
            <div class="tab-pane fade show active" id="recepcion" role="tabpanel">
                <h2 class="mb-3"><i class="fa-solid fa-cash-register me-2"></i>Panel de Recepción</h2>
                
                <div class="card shadow-sm mb-4" style="background-color: #f0f8ff;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Tasa de Cambio BCV</h5>
                            <p class="card-text mb-0" id="fecha-tasa-bcv">Cargando...</p>
                        </div>
                        <div class="text-end">
                            <h3 class="fw-bold mb-0" id="valor-tasa-bcv">Bs. 0,00</h3>
                            <small class="text-muted">1 USD</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Buscar Miembro</h5>
                        <form id="form-buscar-miembro" class="row g-3 align-items-center">
                            <div class="col-auto flex-grow-1">
                                <input type="text" class="form-control" id="cedula-buscar" placeholder="Ingresa la cédula del miembro...">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-magnifying-glass me-2"></i>Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fa-solid fa-history me-2"></i>Registros Recientes</h5>
                        <div id="registros-recientes-container" class="table-responsive"></div>
                    </div>
                </div>
            </div>

            <!-- Pestaña: Gestión de Miembros -->
            <div class="tab-pane fade" id="miembros" role="tabpanel">
                <h2 class="mb-3"><i class="fa-solid fa-users me-2"></i>Administración de Miembros</h2>
                <button class="btn btn-danger mb-3" id="btn-nuevo-miembro"><i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Miembro</button>
                <div id="lista-miembros-container"></div>
            </div>

            <!-- Pestaña: Suscripciones y Promociones -->
            <div class="tab-pane fade" id="suscripciones" role="tabpanel">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="mb-0"><i class="fa-solid fa-tags me-2"></i>Administración de Suscripciones</h2>
                            @if(in_array(session('usuario_rol'), ['admin', 'supervisor']))
                                <button class="btn btn-danger" id="btn-nuevo-plan"><i class="fa-solid fa-plus me-2"></i>Crear Nuevo Plan</button>
                            @endif
                        </div>
                        <div id="lista-planes-container"></div>
                    </div>
                </div>
            </div>

            <!-- Pestaña: Gestión de Entrenadores -->
            <div class="tab-pane fade" id="entrenadores" role="tabpanel">
                <h2 class="mb-3"><i class="fa-solid fa-dumbbell me-2"></i>Administración de Entrenadores</h2>
                @if(in_array(session('usuario_rol'), ['admin', 'supervisor']))
                    <button class="btn btn-danger mb-3" id="btn-nuevo-entrenador"><i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Entrenador</button>
                @endif
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla-entrenadores" class="table table-striped table-hover" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Teléfono</th>
                                        <th>Especialidad</th>
                                        <th>Costo Mensual</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if(in_array(session('usuario_rol'), ['admin', 'supervisor']))
                <div class="tab-pane fade" id="inventario" role="tabpanel">
                    <h2 class="mb-3"><i class="fa-solid fa-boxes-stacked me-2"></i>Administración de Inventario</h2>
                    <button class="btn btn-danger mb-3" id="btn-nuevo-item"><i class="fa-solid fa-plus me-2"></i>Añadir Nuevo Item</button>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla-inventario" class="table table-striped table-hover" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
                                            <th>Departamento</th>
                                            <th>Stock</th>
                                            <th>Precio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('usuario_rol') === 'admin')
                <div class="tab-pane fade" id="analisis" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fa-solid fa-chart-pie me-2"></i>Dashboard de Operaciones del Día</h2>
                        <button class="btn btn-danger" id="btn-generar-pdf-dia">
                            <i class="fa-solid fa-file-pdf me-2"></i>Generar Reporte PDF
                        </button>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-success shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-1">Ingresos por Suscripciones</h6>
                                            <h3 class="fw-bold mb-0" id="total-suscripciones-dia">$0.00</h3>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-id-card fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-1">Ingresos por Ventas</h6>
                                            <h3 class="fw-bold mb-0" id="total-ventas-dia">$0.00</h3>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-shopping-cart fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-primary shadow">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-1">Gran Total del Día</h6>
                                            <h3 class="fw-bold mb-0" id="gran-total-dia">$0.00</h3>
                                        </div>
                                        <div>
                                            <i class="fa-solid fa-dollar-sign fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0"><i class="fa-solid fa-chart-pie me-2"></i>Distribución de Ingresos</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="grafico-ingresos" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0"><i class="fa-solid fa-chart-line me-2"></i>Tendencia Semanal</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="grafico-tendencia" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Detalles -->
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Detalle de Transacciones del Día</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tabla-transacciones-dia" class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Hora</th>
                                            <th>Tipo</th>
                                            <th>Miembro</th>
                                            <th>Concepto</th>
                                            <th>Monto</th>
                                            <th>Método</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <main class="container mt-4" id="contenido-admin" style="display: none;">
        <h2 class="mb-3"><i class="fa-solid fa-users-cog me-2"></i>Administración de Usuarios</h2>
        <button class="btn btn-danger mb-3" id="btn-nuevo-usuario"><i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Usuario</button>
        <div id="lista-usuarios-container"></div>
    </main>

    @include('modales')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Logout
        $('#btn-logout').click(function(e) {
            e.preventDefault();
            $.post('/api/auth/logout', function() {
                window.location.href = '/login';
            });
        });

        // Navegación entre Sistema y Admin
        $('#nav-link-sistema').click(function(e) {
            e.preventDefault();
            $('#contenido-sistema').show();
            $('#contenido-admin').hide();
            $(this).addClass('active');
            $('#nav-link-admin').removeClass('active');
        });

        $('#nav-link-admin').click(function(e) {
            e.preventDefault();
            $('#contenido-admin').show();
            $('#contenido-sistema').hide();
            $(this).addClass('active');
            $('#nav-link-sistema').removeClass('active');
            cargarUsuarios();
        });

        // Cargar miembros
        function cargarMiembros() {
            if ($.fn.DataTable.isDataTable('#tabla-miembros')) {
                $('#tabla-miembros').DataTable().destroy();
            }

            $('#lista-miembros-container').html(`
                <div class="table-responsive">
                    <table id="tabla-miembros" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Foto</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Teléfono</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            `);

            $('#tabla-miembros').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/miembros/listar',
                    type: 'POST',
                    error: function(xhr, error, thrown) {
                        console.error('Error en DataTable:', error, thrown);
                        Swal.fire('Error', 'No se pudieron cargar los miembros', 'error');
                    }
                },
                columns: [
                    { 
                        data: 'ruta_foto', 
                        orderable: false,
                        render: function(data) {
                            return `<img src="/img/default-avatar.svg" alt="Foto" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">`;
                        }
                    },
                    { data: 'nombre' },
                    { data: 'numero_cedula' },
                    { data: 'telefono' },
                    { 
                        data: 'estatus_suscripcion',
                        render: function(data) {
                            let badgeClass = 'bg-success';
                            if (data === 'Vencida') badgeClass = 'bg-danger';
                            else if (data === 'Por Vencer') badgeClass = 'bg-warning';
                            else if (data === 'Sin Suscripción') badgeClass = 'bg-secondary';
                            else if (data === 'Vetado') badgeClass = 'bg-dark';
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                    },
                    { 
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row) {
                            let botonEstado = '';
                            
                            if (row.estatus === 'vetado') {
                                botonEstado = `
                                    <button class="btn btn-sm btn-success btn-reactivar-miembro" data-id="${data}" data-nombre="${row.nombre}" title="Reactivar">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                `;
                            } else {
                                botonEstado = `
                                    <button class="btn btn-sm btn-warning btn-vetar-miembro" data-id="${data}" data-nombre="${row.nombre}" title="Vetar/Suspender">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                `;
                            }
                            
                            return `
                                <button class="btn btn-sm btn-info btn-ver-miembro" data-cedula="${row.numero_cedula}" title="Ver Ficha">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-primary btn-editar-miembro" data-id="${data}" title="Editar">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                ${botonEstado}
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                    processing: 'Procesando...',
                    loadingRecords: 'Cargando...',
                    emptyTable: 'No hay miembros registrados'
                },
                order: [[1, 'asc']]
            });

            // Event handler para ver ficha
            $(document).on('click', '.btn-ver-miembro', function() {
                const cedula = $(this).data('cedula');
                $('#cedula-buscar').val(cedula);
                $('#form-buscar-miembro').submit();
            });
        }

        // Cargar planes
        function cargarPlanes() {
            $.get('/api/planes/listar', function(response) {
                if (response.success) {
                    let html = '<div class="row">';
                    response.data.forEach(plan => {
                        html += `
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${plan.nombre_plan}</h5>
                                        <p class="card-text">${plan.descripcion || ''}</p>
                                        <p class="fw-bold">$${plan.precio_base}</p>
                                        <p class="text-muted">${plan.duracion_dias} días</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    $('#lista-planes-container').html(html);
                }
            });
        }

        // Cargar usuarios
        function cargarUsuarios() {
            $.get('/api/usuarios/listar', function(response) {
                if (response.success) {
                    let html = `
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    response.data.forEach(usuario => {
                        html += `
                            <tr>
                                <td>${usuario.nombre}</td>
                                <td>${usuario.cedula}</td>
                                <td>${usuario.usuario}</td>
                                <td>${usuario.rol}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-editar-usuario" data-id="${usuario.id}" title="Editar">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-usuario" data-id="${usuario.id}" data-nombre="${usuario.nombre}" title="Eliminar">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    html += '</tbody></table>';
                    $('#lista-usuarios-container').html(html);
                }
            }).fail(function() {
                Swal.fire('Error', 'No se pudieron cargar los usuarios', 'error');
            });
        }

        // Event handler para nuevo usuario
        $('#btn-nuevo-usuario').click(function() {
            $('#modal-usuario-titulo').text('Registrar Nuevo Usuario');
            $('#form-usuario')[0].reset();
            $('#usuario_id').val('');
            $('#usuario_clave').attr('required', true);
            $('#modal-usuario').modal('show');
        });

        // Event handler para editar usuario
        $(document).on('click', '.btn-editar-usuario', function() {
            const id = $(this).data('id');
            
            $.get('/api/usuarios/show/' + id, function(response) {
                if (response.success) {
                    const usuario = response.data;
                    $('#modal-usuario-titulo').text('Editar Usuario');
                    $('#usuario_id').val(usuario.id);
                    $('#usuario_nombre').val(usuario.nombre);
                    $('#usuario_cedula').val(usuario.cedula);
                    $('#usuario_telefono').val(usuario.telefono);
                    $('#usuario_usuario').val(usuario.usuario);
                    $('#usuario_clave').val('');
                    $('#usuario_clave').attr('required', false);
                    $('#usuario_rol').val(usuario.rol);
                    $('#modal-usuario').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo cargar el usuario', 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        });

        // Event handler para eliminar usuario
        $(document).on('click', '.btn-eliminar-usuario', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Eliminar Usuario?',
                html: `¿Estás seguro de eliminar a <strong>${nombre}</strong>?<br><br>
                       Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/usuarios/destroy/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Eliminado!', response.message, 'success');
                                cargarUsuarios();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
                        }
                    });
                }
            });
        });

        // Event handler para guardar usuario
        $('#form-usuario').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            $.post('/api/usuarios/store', formData, function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    $('#modal-usuario').modal('hide');
                    cargarUsuarios();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }).fail(function(xhr) {
                let errorMsg = 'No se pudo guardar el usuario';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMsg, 'error');
            });
        });

        // Buscar miembro por cédula
        $('#form-buscar-miembro').submit(function(e) {
            e.preventDefault();
            const cedula = $('#cedula-buscar').val();
            
            if (!cedula) {
                Swal.fire('Error', 'Ingresa una cédula', 'warning');
                return;
            }

            $.get('/api/miembros/cedula', { cedula }, function(response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Determinar color del estatus
                    let estatusColor = 'success';
                    let estatusBadge = 'badge bg-success';
                    if (data.suscripcion_vencida) {
                        estatusColor = 'danger';
                        estatusBadge = 'badge bg-danger';
                    } else if (data.dias_restantes !== null && data.dias_restantes <= 3) {
                        estatusColor = 'warning';
                        estatusBadge = 'badge bg-warning';
                    }

                    // Construir HTML de historial
                    let historialHtml = '';
                    if (data.historial_suscripciones && data.historial_suscripciones.length > 0) {
                        historialHtml = '<div class="mt-3"><h6>Historial de Suscripciones:</h6><div class="table-responsive"><table class="table table-sm"><thead><tr><th>Plan</th><th>Inicio</th><th>Fin</th><th>Monto</th></tr></thead><tbody>';
                        data.historial_suscripciones.forEach(sub => {
                            historialHtml += `<tr><td>${sub.plan}</td><td>${sub.fecha_inicio}</td><td>${sub.fecha_fin}</td><td>$${sub.monto}</td></tr>`;
                        });
                        historialHtml += '</tbody></table></div></div>';
                    }

                    // Botones de acción
                    let botonesHtml = '';
                    if (data.suscripcion_vencida || !data.tiene_suscripcion) {
                        botonesHtml = `<button type="button" class="btn btn-danger mt-3" onclick="renovarSuscripcion(${data.id})">
                            <i class="fa-solid fa-rotate me-2"></i>Renovar Suscripción
                        </button>`;
                    } else if (data.dias_restantes !== null && data.dias_restantes <= 7) {
                        botonesHtml = `<button type="button" class="btn btn-warning mt-3" onclick="renovarSuscripcion(${data.id})">
                            <i class="fa-solid fa-rotate me-2"></i>Renovar Suscripción
                        </button>`;
                    }

                    Swal.fire({
                        title: `<i class="fa-solid fa-user-circle me-2"></i>${data.nombre}`,
                        html: `
                            <div class="text-start">
                                <div class="row mb-3">
                                    <div class="col-12 text-center mb-3">
                                        <img src="/img/default-avatar.svg" 
                                             alt="Foto" 
                                             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #dc3545;">
                                    </div>
                                </div>
                                
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fa-solid fa-id-card me-2"></i>Información Personal</h6>
                                        <p class="mb-1"><strong>Cédula:</strong> ${data.numero_cedula}</p>
                                        <p class="mb-1"><strong>Edad:</strong> ${data.edad} años</p>
                                        <p class="mb-1"><strong>Teléfono:</strong> ${data.telefono}</p>
                                        <p class="mb-1"><strong>Fecha de Registro:</strong> ${data.fecha_registro}</p>
                                    </div>
                                </div>

                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fa-solid fa-dumbbell me-2"></i>Suscripción Actual</h6>
                                        <p class="mb-1"><strong>Plan:</strong> ${data.nombre_membresia}</p>
                                        <p class="mb-1"><strong>Estatus:</strong> <span class="${estatusBadge}">${data.estatus_suscripcion}</span></p>
                                        ${data.fecha_fin_suscripcion ? `
                                            <p class="mb-1"><strong>Fecha de Inicio:</strong> ${data.fecha_inicio_suscripcion}</p>
                                            <p class="mb-1"><strong>Fecha de Vencimiento:</strong> ${data.fecha_fin_suscripcion}</p>
                                            <p class="mb-1"><strong>Días Restantes:</strong> 
                                                <span class="badge bg-${estatusColor}">${data.dias_restantes >= 0 ? data.dias_restantes : 'Vencida'}</span>
                                            </p>
                                            <p class="mb-1"><strong>Último Pago:</strong> $${data.monto_pagado}</p>
                                        ` : '<p class="text-danger">Sin suscripción activa</p>'}
                                    </div>
                                </div>

                                ${historialHtml}
                                
                                <div class="text-center">
                                    ${botonesHtml}
                                    <button type="button" class="btn btn-success mt-2" onclick="abrirModalVenta(${data.id}, '${data.nombre}')">
                                        <i class="fa-solid fa-cart-shopping me-2"></i>Realizar Venta
                                    </button>
                                </div>
                            </div>
                        `,
                        width: '600px',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar',
                        customClass: {
                            confirmButton: 'btn btn-secondary'
                        }
                    });
                } else {
                    Swal.fire('No encontrado', response.message, 'warning');
                }
            }).fail(function() {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        });

        // Función para abrir modal de venta
        window.abrirModalVenta = function(miembroId, nombreMiembro) {
            Swal.close();
            
            // Configurar modal
            $('#venta-miembro-id').val(miembroId);
            $('#venta-nombre-miembro').text(nombreMiembro);
            
            // Limpiar carrito
            window.carritoVenta = [];
            actualizarCarritoVenta();
            
            // Limpiar campos de método de pago y referencia
            $('#venta-metodo-pago').val('');
            $('#venta-referencia').val('');
            $('#campo-referencia-venta').hide();
            
            // Cargar productos de tienda
            $.get('/api/inventario/listar', function(response) {
                if (response.success) {
                    const productos = response.data.filter(item => item.tipo === 'Tienda' && item.stock > 0);
                    
                    $('#venta-producto-select').empty();
                    $('#venta-producto-select').append('<option value="">Selecciona un producto...</option>');
                    
                    productos.forEach(producto => {
                        $('#venta-producto-select').append(
                            `<option value="${producto.id}" data-nombre="${producto.nombre_item}" data-precio="${producto.precio}" data-stock="${producto.stock}">
                                ${producto.nombre_item} - $${producto.precio} (Stock: ${producto.stock})
                            </option>`
                        );
                    });
                    
                    // Inicializar Select2 si está disponible
                    if ($.fn.select2) {
                        $('#venta-producto-select').select2({
                            dropdownParent: $('#modal-realizar-venta'),
                            placeholder: 'Buscar producto...',
                            width: '100%'
                        });
                    }
                    
                    $('#modal-realizar-venta').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudieron cargar los productos', 'error');
                }
            });
        };

        // Carrito de venta
        window.carritoVenta = [];

        // Agregar producto al carrito
        $('#btn-agregar-producto').click(function() {
            const productoId = $('#venta-producto-select').val();
            const cantidad = parseInt($('#venta-cantidad').val());
            
            if (!productoId) {
                Swal.fire('Error', 'Selecciona un producto', 'warning');
                return;
            }
            
            const option = $('#venta-producto-select option:selected');
            const nombre = option.data('nombre');
            const precio = parseFloat(option.data('precio'));
            const stock = parseInt(option.data('stock'));
            
            if (cantidad > stock) {
                Swal.fire('Error', `Stock insuficiente. Disponible: ${stock}`, 'warning');
                return;
            }
            
            // Verificar si ya está en el carrito
            const existe = window.carritoVenta.find(item => item.id == productoId);
            if (existe) {
                if (existe.cantidad + cantidad > stock) {
                    Swal.fire('Error', `Stock insuficiente. Disponible: ${stock}`, 'warning');
                    return;
                }
                existe.cantidad += cantidad;
            } else {
                window.carritoVenta.push({
                    id: productoId,
                    nombre: nombre,
                    precio: precio,
                    cantidad: cantidad,
                    stock: stock
                });
            }
            
            actualizarCarritoVenta();
            $('#venta-cantidad').val(1);
        });

        // Actualizar vista del carrito
        function actualizarCarritoVenta() {
            let html = '';
            let total = 0;
            
            if (window.carritoVenta.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted">El carrito está vacío</td></tr>';
            } else {
                window.carritoVenta.forEach((item, index) => {
                    const subtotal = item.precio * item.cantidad;
                    total += subtotal;
                    html += `
                        <tr>
                            <td>${item.nombre}</td>
                            <td>${item.cantidad}</td>
                            <td>$${item.precio.toFixed(2)}</td>
                            <td>$${subtotal.toFixed(2)}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" onclick="eliminarDelCarrito(${index})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
            
            $('#venta-carrito-body').html(html);
            $('#venta-total-usd').text('$' + total.toFixed(2));
            
            // Calcular en bolívares (obtener tasa actual)
            const tasaBcv = parseFloat($('#valor-tasa-bcv').text().replace('Bs. ', '').replace(',', ''));
            if (tasaBcv > 0) {
                $('#venta-total-bs').text('Bs. ' + (total * tasaBcv).toFixed(2));
            }
        }

        // Eliminar del carrito
        window.eliminarDelCarrito = function(index) {
            window.carritoVenta.splice(index, 1);
            actualizarCarritoVenta();
        };

        // Mostrar/ocultar campo de referencia según método de pago
        $('#venta-metodo-pago').change(function() {
            const metodoPago = $(this).val();
            if (metodoPago === 'Punto de Venta (Bs.)' || metodoPago === 'Pago Móvil (Bs.)') {
                $('#campo-referencia-venta').show();
                $('#venta-referencia').attr('required', true);
            } else {
                $('#campo-referencia-venta').hide();
                $('#venta-referencia').attr('required', false);
                $('#venta-referencia').val('');
            }
        });

        // Finalizar venta
        $('#form-realizar-venta').submit(function(e) {
            e.preventDefault();
            
            if (window.carritoVenta.length === 0) {
                Swal.fire('Error', 'El carrito está vacío', 'warning');
                return;
            }

            const metodoPago = $('#venta-metodo-pago').val();
            if (!metodoPago) {
                Swal.fire('Error', 'Selecciona un método de pago', 'warning');
                return;
            }

            const referencia = $('#venta-referencia').val();
            if ((metodoPago === 'Punto de Venta (Bs.)' || metodoPago === 'Pago Móvil (Bs.)') && !referencia) {
                Swal.fire('Error', 'Ingresa el número de referencia', 'warning');
                return;
            }
            
            const miembroId = $('#venta-miembro-id').val();
            const items = window.carritoVenta.map(item => ({
                inventario_id: item.id,
                cantidad: item.cantidad
            }));
            
            $.post('/api/ventas/store', {
                miembro_id: miembroId,
                items: items,
                metodo_pago: metodoPago,
                referencia_pago: referencia
            }, function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    $('#modal-realizar-venta').modal('hide');
                    cargarRegistrosRecientes();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }).fail(function(xhr) {
                let errorMsg = 'No se pudo procesar la venta';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMsg, 'error');
            });
        });

        // Función para renovar suscripción
        window.renovarSuscripcion = function(miembroId) {
            Swal.close();
            
            // Cargar planes disponibles
            $.get('/api/planes/listar', function(response) {
                if (response.success && response.data.length > 0) {
                    let planesOptions = '';
                    response.data.forEach(plan => {
                        planesOptions += `<option value="${plan.id}" data-precio="${plan.precio_base}" data-duracion="${plan.duracion_dias}">
                            ${plan.nombre_plan} - $${plan.precio_base} (${plan.duracion_dias} días)
                        </option>`;
                    });

                    Swal.fire({
                        title: 'Renovar Suscripción',
                        html: `
                            <form id="form-renovar-suscripcion" class="text-start">
                                <div class="mb-3">
                                    <label class="form-label">Plan</label>
                                    <select class="form-select" id="plan-renovar" required>
                                        <option value="">Selecciona un plan</option>
                                        ${planesOptions}
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="fecha-inicio-renovar" 
                                           value="${new Date().toISOString().split('T')[0]}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Monto a Pagar ($)</label>
                                    <input type="number" step="0.01" class="form-control" id="monto-renovar" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Método de Pago</label>
                                    <select class="form-select" id="metodo-pago-renovar" required>
                                        <option value="Efectivo $">Efectivo $</option>
                                        <option value="Efectivo Bs.">Efectivo Bs.</option>
                                        <option value="Pago Móvil (Bs.)">Pago Móvil (Bs.)</option>
                                        <option value="Transferencia (Bs.)">Transferencia (Bs.)</option>
                                        <option value="Zelle">Zelle</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Referencia de Pago</label>
                                    <input type="text" class="form-control" id="referencia-renovar">
                                </div>
                            </form>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Renovar',
                        cancelButtonText: 'Cancelar',
                        preConfirm: () => {
                            const planId = $('#plan-renovar').val();
                            const fechaInicio = $('#fecha-inicio-renovar').val();
                            const monto = $('#monto-renovar').val();
                            const metodoPago = $('#metodo-pago-renovar').val();
                            const referencia = $('#referencia-renovar').val();

                            if (!planId || !fechaInicio || !monto) {
                                Swal.showValidationMessage('Por favor completa todos los campos requeridos');
                                return false;
                            }

                            return {
                                miembro_id: miembroId,
                                plan_id: planId,
                                fecha_inicio: fechaInicio,
                                monto_pagado: monto,
                                metodo_pago: metodoPago,
                                referencia_pago: referencia
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('/api/suscripciones/store', result.value, function(response) {
                                if (response.success) {
                                    Swal.fire('¡Éxito!', response.message, 'success');
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            }).fail(function() {
                                Swal.fire('Error', 'No se pudo procesar la renovación', 'error');
                            });
                        }
                    });

                    // Auto-completar monto cuando se selecciona un plan
                    $('#plan-renovar').change(function() {
                        const precio = $(this).find(':selected').data('precio');
                        $('#monto-renovar').val(precio);
                    });
                } else {
                    Swal.fire('Error', 'No hay planes disponibles', 'error');
                }
            });
        };

        // Cargar tasa BCV
        function cargarTasaBCV() {
            $.get('/api/bcv/tasa', function(response) {
                if (response.success) {
                    $('#valor-tasa-bcv').text('Bs. ' + parseFloat(response.data.valor).toFixed(2));
                    $('#fecha-tasa-bcv').text('Actualizado: ' + response.data.fecha);
                }
            });
        }

        // Cargar dashboard
        function cargarDashboard() {
            $.get('/api/reportes/dashboard-dia', function(response) {
                if (response.success) {
                    $('#total-suscripciones-dia').text('$' + response.data.total_suscripciones);
                    $('#total-ventas-dia').text('$' + response.data.total_ventas);
                    $('#gran-total-dia').text('$' + response.data.gran_total);
                }
            });
        }

        // Cargar registros recientes
        function cargarRegistrosRecientes() {
            $.get('/api/suscripciones/registros-recientes', function(response) {
                if (response.success) {
                    let html = `
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Miembro</th>
                                    <th>Plan</th>
                                    <th>Monto</th>
                                    <th>Método</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    
                    if (response.data.length > 0) {
                        response.data.forEach(registro => {
                            html += `
                                <tr>
                                    <td>${registro.miembro_nombre}</td>
                                    <td>${registro.plan_nombre}</td>
                                    <td>$${registro.monto_pagado}</td>
                                    <td>${registro.metodo_pago || 'N/A'}</td>
                                    <td>${registro.fecha_registro}</td>
                                </tr>
                            `;
                        });
                    } else {
                        html += '<tr><td colspan="5" class="text-center">No hay registros hoy</td></tr>';
                    }
                    
                    html += '</tbody></table>';
                    $('#registros-recientes-container').html(html);
                }
            });
        }

        // Event handler para nuevo miembro
        $('#btn-nuevo-miembro').click(function() {
            $('#modal-miembro-titulo').text('Registrar Nuevo Miembro');
            $('#form-miembro')[0].reset();
            $('#miembro_id').val('');
            $('#modal-miembro').modal('show');
        });

        // Event handler para editar miembro
        $(document).on('click', '.btn-editar-miembro', function() {
            const id = $(this).data('id');
            
            $.get('/api/miembros/show/' + id, function(response) {
                if (response.success) {
                    const miembro = response.data;
                    $('#modal-miembro-titulo').text('Editar Miembro');
                    $('#miembro_id').val(miembro.id);
                    $('#nombre').val(miembro.nombre);
                    $('#telefono').val(miembro.telefono);
                    $('#numero_cedula').val(miembro.numero_cedula);
                    $('#fecha_nacimiento').val(miembro.fecha_nacimiento);
                    $('#modal-miembro').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo cargar el miembro', 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        });

        // Event handler para vetar/suspender miembro
        $(document).on('click', '.btn-vetar-miembro', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Vetar/Suspender Miembro?',
                html: `¿Estás seguro de suspender temporalmente a <strong>${nombre}</strong>?<br><br>
                       El miembro no podrá acceder al gimnasio hasta que sea reactivado.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, suspender',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('/api/miembros/vetar/' + id, function(response) {
                        if (response.success) {
                            Swal.fire('¡Suspendido!', response.message, 'success');
                            cargarMiembros();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }).fail(function() {
                        Swal.fire('Error', 'No se pudo suspender el miembro', 'error');
                    });
                }
            });
        });

        // Event handler para reactivar miembro
        $(document).on('click', '.btn-reactivar-miembro', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            
            Swal.fire({
                title: '¿Reactivar Miembro?',
                html: `¿Estás seguro de reactivar a <strong>${nombre}</strong>?<br><br>
                       El miembro podrá acceder nuevamente al gimnasio.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, reactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('/api/miembros/reactivar/' + id, function(response) {
                        if (response.success) {
                            Swal.fire('¡Reactivado!', response.message, 'success');
                            cargarMiembros();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }).fail(function() {
                        Swal.fire('Error', 'No se pudo reactivar el miembro', 'error');
                    });
                }
            });
        });

        // Event handler para guardar miembro
        $('#form-miembro').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: '/api/miembros/store',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('¡Éxito!', response.message, 'success');
                        $('#modal-miembro').modal('hide');
                        if ($.fn.DataTable.isDataTable('#tabla-miembros')) {
                            $('#tabla-miembros').DataTable().ajax.reload();
                        }
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'No se pudo guardar el miembro';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });

        // Event handler para nuevo plan
        $('#btn-nuevo-plan').click(function() {
            $('#modal-plan-titulo').text('Crear Nuevo Plan');
            $('#form-plan')[0].reset();
            $('#plan_id').val('');
            $('#modal-plan').modal('show');
        });

        // Event handler para guardar plan
        $('#form-plan').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            $.post('/api/planes/store', formData, function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    $('#modal-plan').modal('hide');
                    cargarPlanes();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'No se pudo guardar el plan', 'error');
            });
        });

        // Event handler para nuevo item de inventario
        $('#btn-nuevo-item').click(function() {
            $('#modal-equipo-titulo').text('Añadir Nuevo Item');
            $('#form-equipo')[0].reset();
            $('#equipo_id').val('');
            $('#tipo').val('Tienda');
            $('#modal-equipo').modal('show');
        });

        // Establecer tipo automáticamente según departamento
        $('#departamento').change(function() {
            const departamento = $(this).val();
            const departamentosTienda = ['Suplementos', 'Bebidas', 'Accesorios de Venta', 'Lenceria'];
            
            if (departamentosTienda.includes(departamento)) {
                $('#tipo').val('Tienda');
            } else {
                $('#tipo').val('Operaciones');
            }
        });

        // Event handler para guardar item de inventario
        $('#form-equipo').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            $.post('/api/inventario/store', formData, function(response) {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    $('#modal-equipo').modal('hide');
                    if ($.fn.DataTable.isDataTable('#tabla-inventario')) {
                        $('#tabla-inventario').DataTable().ajax.reload();
                    }
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }).fail(function(xhr) {
                let errorMsg = 'No se pudo guardar el item';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMsg = errors.join('<br>');
                }
                Swal.fire('Error', errorMsg, 'error');
            });
        });

        // Event handler para editar item de inventario
        $(document).on('click', '.btn-editar-item', function() {
            const id = $(this).data('id');
            
            $.get('/api/inventario/show/' + id, function(response) {
                if (response.success) {
                    const item = response.data;
                    $('#modal-equipo-titulo').text('Editar Item');
                    $('#equipo_id').val(item.id);
                    $('#nombre_item').val(item.nombre_item);
                    $('#descripcion').val(item.descripcion);
                    $('#departamento').val(item.departamento);
                    $('#estado').val(item.estado);
                    $('#stock').val(item.stock);
                    $('#precio').val(item.precio);
                    $('#fecha_adquisicion').val(item.fecha_adquisicion);
                    $('#modal-equipo').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo cargar el item', 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        });

        // Event handler para eliminar item de inventario
        $(document).on('click', '.btn-eliminar-item', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: '¿Eliminar Item?',
                text: '¿Estás seguro de eliminar este item del inventario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/inventario/destroy/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Eliminado!', response.message, 'success');
                                if ($.fn.DataTable.isDataTable('#tabla-inventario')) {
                                    $('#tabla-inventario').DataTable().ajax.reload();
                                }
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el item', 'error');
                        }
                    });
                }
            });
        });

        // Cargar inventario
        function cargarInventario() {
            if ($.fn.DataTable.isDataTable('#tabla-inventario')) {
                $('#tabla-inventario').DataTable().destroy();
            }

            $('#tabla-inventario').DataTable({
                ajax: {
                    url: '/api/inventario/listar',
                    dataSrc: function(json) {
                        return json.success ? json.data : [];
                    }
                },
                columns: [
                    { data: 'codigo_item' },
                    { data: 'nombre_item' },
                    { data: 'tipo' },
                    { data: 'departamento' },
                    { data: 'stock' },
                    { 
                        data: 'precio',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-primary btn-editar-item" data-id="${data}" title="Editar">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-item" data-id="${data}" title="Eliminar">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                order: [[1, 'asc']]
            });
        }

        // ========================================
        // CRUD DE ENTRENADORES
        // ========================================

        // Cargar entrenadores
        function cargarEntrenadores() {
            if ($.fn.DataTable.isDataTable('#tabla-entrenadores')) {
                $('#tabla-entrenadores').DataTable().destroy();
            }

            $('#tabla-entrenadores').DataTable({
                ajax: {
                    url: '/api/entrenadores/listar',
                    dataSrc: function(json) {
                        return json.success ? json.data : [];
                    }
                },
                columns: [
                    { 
                        data: 'ruta_foto', 
                        orderable: false,
                        render: function(data) {
                            return `<img src="/img/default-avatar.svg" alt="Foto" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">`;
                        }
                    },
                    { data: 'nombre_completo' },
                    { data: 'numero_cedula' },
                    { data: 'telefono' },
                    { data: 'especialidad' },
                    { 
                        data: 'costo_mensual',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { 
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `
                                <button class="btn btn-sm btn-primary btn-editar-entrenador" data-id="${data}" title="Editar">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-entrenador" data-id="${data}" title="Eliminar">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                    processing: 'Procesando...',
                    loadingRecords: 'Cargando...',
                    emptyTable: 'No hay entrenadores registrados'
                },
                order: [[1, 'asc']]
            });
        }

        // Event handler para nuevo entrenador
        $('#btn-nuevo-entrenador').click(function() {
            $('#modal-entrenador-titulo').text('Registrar Nuevo Entrenador');
            $('#form-entrenador')[0].reset();
            $('#entrenador_id').val('');
            $('#modal-entrenador').modal('show');
        });

        // Event handler para editar entrenador
        $(document).on('click', '.btn-editar-entrenador', function() {
            const id = $(this).data('id');
            
            $.get('/api/entrenadores/show/' + id, function(response) {
                if (response.success) {
                    const entrenador = response.data;
                    $('#modal-entrenador-titulo').text('Editar Entrenador');
                    $('#entrenador_id').val(entrenador.id);
                    $('#nombre_completo_entrenador').val(entrenador.nombre_completo);
                    $('#cedula_entrenador').val(entrenador.numero_cedula);
                    $('#telefono_entrenador').val(entrenador.telefono);
                    $('#email_entrenador').val(entrenador.email || '');
                    $('#especialidad_entrenador').val(entrenador.especialidad || '');
                    $('#costo_mensual_entrenador').val(entrenador.costo_mensual);
                    $('#modal-entrenador').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo cargar el entrenador', 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        });

        // Event handler para guardar entrenador
        $('#form-entrenador').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            $.ajax({
                url: '/api/entrenadores/store',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('¡Éxito!', response.message, 'success');
                        $('#modal-entrenador').modal('hide');
                        if ($.fn.DataTable.isDataTable('#tabla-entrenadores')) {
                            $('#tabla-entrenadores').DataTable().ajax.reload();
                        }
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'No se pudo guardar el entrenador';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });

        // Event handler para eliminar entrenador
        $(document).on('click', '.btn-eliminar-entrenador', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/entrenadores/destroy/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Eliminado!', response.message, 'success');
                                if ($.fn.DataTable.isDataTable('#tabla-entrenadores')) {
                                    $('#tabla-entrenadores').DataTable().ajax.reload();
                                }
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'No se pudo eliminar el entrenador', 'error');
                        }
                    });
                }
            });
        });

        // ========================================
        // DASHBOARD Y REPORTES
        // ========================================

        let graficoIngresos = null;
        let graficoTendencia = null;

        // Cargar dashboard
        function cargarDashboard() {
            $.get('/api/reportes/dashboard-dia', function(response) {
                if (response.success) {
                    $('#total-suscripciones-dia').text('$' + response.data.total_suscripciones);
                    $('#total-ventas-dia').text('$' + response.data.total_ventas);
                    $('#gran-total-dia').text('$' + response.data.gran_total);

                    // Crear gráfico de distribución
                    crearGraficoIngresos(response.data);

                    // Cargar tendencia semanal
                    cargarTendenciaSemanal();

                    // Cargar tabla de transacciones
                    cargarTablaTransacciones();
                }
            });
        }

        // Crear gráfico de distribución de ingresos
        function crearGraficoIngresos(data) {
            const ctx = document.getElementById('grafico-ingresos');
            if (!ctx) return;

            // Destruir gráfico anterior si existe
            if (graficoIngresos) {
                graficoIngresos.destroy();
            }

            graficoIngresos = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Suscripciones', 'Ventas'],
                    datasets: [{
                        data: [
                            parseFloat(data.total_suscripciones) || 0,
                            parseFloat(data.total_ventas) || 0
                        ],
                        backgroundColor: [
                            'rgba(25, 135, 84, 0.8)',
                            'rgba(13, 202, 240, 0.8)'
                        ],
                        borderColor: [
                            'rgba(25, 135, 84, 1)',
                            'rgba(13, 202, 240, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': $' + context.parsed.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Cargar tendencia semanal
        function cargarTendenciaSemanal() {
            $.get('/api/reportes/tendencia-semanal', function(response) {
                if (response.success) {
                    const ctx = document.getElementById('grafico-tendencia');
                    if (!ctx) return;

                    // Destruir gráfico anterior si existe
                    if (graficoTendencia) {
                        graficoTendencia.destroy();
                    }

                    graficoTendencia = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.data.labels || ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                            datasets: [{
                                label: 'Ingresos Totales',
                                data: response.data.valores || [0, 0, 0, 0, 0, 0, 0],
                                borderColor: 'rgba(13, 110, 253, 1)',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Total: $' + context.parsed.y.toFixed(2);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '$' + value;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }).fail(function() {
                // Si falla, crear gráfico vacío
                const ctx = document.getElementById('grafico-tendencia');
                if (!ctx) return;

                if (graficoTendencia) {
                    graficoTendencia.destroy();
                }

                graficoTendencia = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                        datasets: [{
                            label: 'Ingresos Totales',
                            data: [0, 0, 0, 0, 0, 0, 0],
                            borderColor: 'rgba(13, 110, 253, 1)',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        }

        // Cargar tabla de transacciones
        function cargarTablaTransacciones() {
            if ($.fn.DataTable.isDataTable('#tabla-transacciones-dia')) {
                $('#tabla-transacciones-dia').DataTable().destroy();
            }

            $('#tabla-transacciones-dia').DataTable({
                ajax: {
                    url: '/api/reportes/transacciones-dia',
                    dataSrc: function(json) {
                        return json.success ? json.data : [];
                    }
                },
                columns: [
                    { data: 'hora' },
                    { 
                        data: 'tipo',
                        render: function(data) {
                            if (data === 'Suscripción') {
                                return '<span class="badge bg-success">Suscripción</span>';
                            } else {
                                return '<span class="badge bg-info">Venta</span>';
                            }
                        }
                    },
                    { data: 'miembro' },
                    { data: 'concepto' },
                    { 
                        data: 'monto',
                        render: function(data) {
                            return '$' + parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'metodo_pago' }
                ],
                language: {
                    url: '/js/i18n/es-ES.json'
                },
                order: [[0, 'desc']],
                pageLength: 10
            });
        }

        // Generar PDF del día
        $('#btn-generar-pdf-dia').click(function() {
            Swal.fire({
                title: 'Generando Reporte...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.open('/api/reportes/pdf-dia', '_blank');
            
            setTimeout(function() {
                Swal.close();
            }, 1000);
        });

        // Inicializar
        $(document).ready(function() {
            // Cargar tasa BCV al inicio
            cargarTasaBCV();
            
            // Cargar registros recientes al inicio
            cargarRegistrosRecientes();
            
            // Recargar registros cada 30 segundos
            setInterval(cargarRegistrosRecientes, 30000);

            $('#miembros-tab').on('shown.bs.tab', function() {
                cargarMiembros();
            });

            $('#suscripciones-tab').on('shown.bs.tab', function() {
                cargarPlanes();
            });

            $('#entrenadores-tab').on('shown.bs.tab', function() {
                cargarEntrenadores();
            });

            $('#inventario-tab').on('shown.bs.tab', function() {
                cargarInventario();
            });

            $('#analisis-tab').on('shown.bs.tab', function() {
                cargarDashboard();
            });
        });
    </script>
</body>
</html>
