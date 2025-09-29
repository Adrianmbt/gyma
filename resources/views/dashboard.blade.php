@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Pestañas de Navegación -->
    <ul class="nav nav-tabs justify-content-center mb-4" id="mainTabs" role="tablist">
      <li class="nav-item" role="presentation"><button class="nav-link active" id="recepcion-tab" data-bs-toggle="tab" data-bs-target="#recepcion" type="button" role="tab">Recepción y Ventas</button></li>
      <li class="nav-item" role="presentation"><button class="nav-link" id="miembros-tab" data-bs-toggle="tab" data-bs-target="#miembros" type="button" role="tab">Gestión de Miembros</button></li>
      <li class="nav-item" role="presentation"><button class="nav-link" id="suscripciones-tab" data-bs-toggle="tab" data-bs-target="#suscripciones" type="button" role="tab">Suscripciones y Promociones</button></li>
      <li class="nav-item" role="presentation"><button class="nav-link" id="entrenadores-tab" data-bs-toggle="tab" data-bs-target="#entrenadores" type="button" role="tab">Gestión de Entrenadores</button></li>
    
      @if (in_array(Auth::user()->rol, ['admin', 'supervisor']))
        <li class="nav-item" role="presentation"><button class="nav-link" id="inventario-tab" data-bs-toggle="tab" data-bs-target="#inventario" type="button" role="tab">Gestión de Inventario</button></li>
      @endif
    
      @if (Auth::user()->rol === 'admin')
        <li class="nav-item" role="presentation"><button class="nav-link" id="analisis-tab" data-bs-toggle="tab" data-bs-target="#analisis" type="button" role="tab">Análisis y Reportes</button></li>
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
                        <p class="card-text mb-0" id="fecha-tasa-bcv">Cargando fecha...</p>
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
                            <label for="cedula-buscar" class="visually-hidden">Cédula</label>
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
                    <h5 class="card-title mb-3"><i class="fa-solid fa-history me-2"></i>Registros Recientes (Suscripciones y Ventas)</h5>
                    <div id="registros-recientes-container" class="table-responsive">
                        <!-- La DataTable se inyectará aquí -->
                    </div>
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
                        @if (in_array(Auth::user()->rol, ['admin', 'supervisor']))
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
            @if (in_array(Auth::user()->rol, ['admin', 'supervisor']))
                <button class="btn btn-danger mb-3" id="btn-abrir-modal-entrenador"><i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Entrenador</button>
            @endif
            <div id="lista-entrenadores-container"></div>
        </div>

        @if (in_array(Auth::user()->rol, ['admin', 'supervisor']))
            <div class="tab-pane fade" id="inventario" role="tabpanel">
                <h2 class="mb-3"><i class="fa-solid fa-boxes-stacked me-2"></i>Administración de Inventario Unificado</h2>
                <div class="card shadow-sm"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3"><h5 class="card-title mb-0">Listado General de Inventario</h5><button class="btn btn-danger btn-pulse" id="btn-nuevo-item"><i class="fa-solid fa-plus me-2"></i>Añadir Nuevo Item</button></div><div class="table-responsive"><table id="tabla-inventario" class="table table-striped table-hover" style="width:100%"><thead class="table-dark"><tr><th>Código</th><th>Nombre</th><th>Descripción</th><th>Categoría/Tipo</th><th>Estado</th><th>Precio/Adquisición</th><th>Stock</th><th>Acciones</th></tr></thead><tbody></tbody></table></div></div></div>
            </div>
        @endif

        @if (Auth::user()->rol === 'admin')
            <div class="tab-pane fade" id="analisis" role="tabpanel">
                <h2 class="mb-4"><i class="fa-solid fa-chart-pie me-2"></i>Dashboard de Operaciones del Día</h2>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-success shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h5 class="card-title">Ingresos por Suscripciones</h5><h3 class="fw-bold" id="total-suscripciones-dia">$0.00</h3></div><i class="fa-solid fa-id-card fa-3x opacity-50"></i></div></div></div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-info shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h5 class="card-title">Ingresos por Ventas</h5><h3 class="fw-bold" id="total-ventas-dia">$0.00</h3></div><i class="fa-solid fa-cash-register fa-3x opacity-50"></i></div></div></div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-primary shadow"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h5 class="card-title">Gran Total del Día</h5><h3 class="fw-bold" id="gran-total-dia">$0.00</h3></div><i class="fa-solid fa-sack-dollar fa-3x opacity-50"></i></div></div></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reporte Fiscal Detallado</h5>
                        <p class="card-text">Genera un documento PDF con el resumen detallado de todas las transacciones del día actual.</p>
                        <a href="{{ route('reportes.diario.pdf') }}" class="btn btn-danger" target="_blank"><i class="fa-solid fa-file-pdf me-2"></i>Generar Reporte del Día (PDF)</a>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="admin-settings" role="tabpanel">
                <h2 class="mb-3"><i class="fa-solid fa-users-cog me-2"></i>Administración de Usuarios</h2>
                <button class="btn btn-danger mb-3" id="btn-nuevo-usuario"><i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Usuario</button>
                <div id="lista-usuarios-container">
                    <div class="table-responsive">
                        <table id="tabla-usuarios" class="table table-striped table-hover" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Cédula</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
