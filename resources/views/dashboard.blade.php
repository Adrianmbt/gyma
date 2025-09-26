<x-app-layout>
    <x-slot name="header">
        {{-- El header se puede dejar vacío o personalizarlo más adelante --}}
    </x-slot>

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
            <li class="nav-item" role="presentation"><button class="nav-link" id="admin-settings-tab" data-bs-toggle="tab" data-bs-target="#admin-settings" type="button" role="tab">Admin Settings</button></li>
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

    {{-- Modales --}}
    <div id="modals-container">
        <!-- Modal: Pagar / Asignar Suscripción -->
        <div class="modal fade" id="modal-pago-suscripcion" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Suscripción para <span id="pago-nombre-miembro"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="form-pago-suscripcion" novalidate>
                  <input type="hidden" id="pago-miembro-id" name="miembro_id">
                  
                  <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pago-plan-id" class="form-label">Plan <span class="text-danger">*</span></label>
                            <select class="form-select" id="pago-plan-id" name="plan_id" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="pago-promocion-id" class="form-label">Promoción (Opcional)</label>
                            <select class="form-select" id="pago-promocion-id" name="promocion_id"></select>
                        </div>
                         <div class="mb-3">
                            <label for="pago-entrenador-id" class="form-label">Entrenador (Opcional)</label>
                            <select class="form-select" id="pago-entrenador-id" name="entrenador_id"></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pago-metodo-pago" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                            <select class="form-select" id="pago-metodo-pago" name="metodo_pago" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="Divisa ($)">Divisa ($)</option>
                                <option value="Punto de Venta (Bs.)">Punto de Venta (Bs.)</option>
                                <option value="Pago Móvil (Bs.)">Pago Móvil (Bs.)</option>
                            </select>
                        </div>
                        <div class="mb-3" id="campo-referencia-pago" style="display: none;">
                            <label for="pago-referencia" class="form-label">Número de Referencia <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pago-referencia" name="referencia_pago">
                        </div>
                        <div id="pago-entrenador-detalles" class="p-3 bg-light rounded border" style="display: none; min-height: 80px;">
                            <h6 class="mb-2">Entrenador Seleccionado</h6>
                            <small>Especialidad: <strong id="detalle-pago-entrenador-especialidad">N/A</strong></small>
                        </div>
                    </div>
                  </div>
                  
                  <hr>
                  
                  <div id="pago-detalles-plan" class="p-3 mb-3 bg-light rounded border" style="display: none;">
                    <!-- El contenido de los detalles del pago se genera dinámicamente aquí -->
                  </div>
        
                  <div class="alert alert-info text-center mt-3">
                    <h5>Total a Pagar</h5>
                    <strong id="pago-total-usd" style="font-size: 1.8rem;">$0.00</strong> / 
                    <strong id="pago-total-bs" style="font-size: 1.4rem;">Bs. 0,00</strong>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger" form="form-pago-suscripcion">Confirmar Pago</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal: Ficha Técnica del Miembro -->
        <div class="modal fade" id="modal-ficha-miembro" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="modal-ficha-titulo"><i class="fa-solid fa-id-card me-2"></i>Ficha Técnica</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <img id="ficha-foto" src="{{ asset('storage/uploads/default.png') }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #dee2e6;" alt="Foto del Miembro">
                            <h3 class="mt-3" id="ficha-nombre">Nombre del Miembro</h3>
                            <div class="align-items-center">
                        <div class="list-group-item"><strong>Membresía:</strong> <span id="ficha-membresia-nombre" class="float-end">N/A</span></div>
                        <div class="list-group-item"><strong>Estado Membresía:</strong> <span id="ficha-membresia-estado" class="float-end">N/A</span></div>
                        <div class="list-group-item"><strong>Vencimiento:</strong> <span id="ficha-membresia-vencimiento" class="float-end">N/A</span></div>
                    </div>
                      </div>
                        </div>
                        <div class="list-group">
                            <div class="list-group-item"><strong>Cédula:</strong> <span id="ficha-cedula" class="float-end">N/A</span></div>
                            <div class="list-group-item"><strong>Teléfono:</strong> <span id="ficha-telefono" class="float-end">N/A</span></div>
                            <div class="list-group-item"><strong>Nacimiento:</strong> <span id="ficha-nacimiento" class="float-end">N/A</span></div>
                            <div class="list-group-item"><strong>Edad:</strong> <span id="ficha-edad" class="float-end">N/A</span></div>
                        </div>
                        <hr>
                        <h5 class="text-center mb-3">Acciones Rápidas</h5>
                        <div class="d-grid gap-2">
                            <button class="btn btn-danger btn-lg" id="btn-pagar-suscripcion"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Suscripción y Pagos</button>
                            <button class="btn btn-success btn-lg" id="btn-realizar-venta"><i class="fa-solid fa-cart-shopping me-2"></i>Realizar Venta</button>
                        </div>
                    </div>
                </div>
            </div>
        
        <!-- Modal: Miembro CRUD -->
        <div class="modal fade" id="modal-miembro" tabindex="-1" aria-labelledby="modal-miembro-titulo" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-secondary text-white justify-content-center"><h5 class="modal-title" id="modal-miembro-titulo">Registrar Nuevo Miembro</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button></div>
              <div class="modal-body">
                <form id="form-miembro" enctype="multipart/form-data" novalidate>
                  <input type="hidden" id="miembro_id" name="miembro_id">
                  <div class="mb-3"><label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label><input type="text" class="form-control" id="nombre" name="nombre" required></div>
                  <div class="mb-3"><label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label><input type="text" class="form-control" id="telefono" name="telefono" required></div>
                  <div class="mb-3"><label for="numero_cedula" class="form-label">Número de Cédula <span class="text-danger">*</span></label><input type="text" class="form-control" id="numero_cedula" name="numero_cedula" required></div>
                  <div class="mb-3"><label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label><input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required></div>
                  <div class="mb-3"><label for="foto" class="form-label">Foto del Miembro</label><input class="form-control" type="file" id="foto" name="foto" accept="image/*"></div>
                </form>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-danger" form="form-miembro" id="btn-guardar-miembro">Guardar Cambios</button></div>
            </div>
          </div>
        </div>
        
        <!-- Modal: Inventario CRUD -->
        <div class="modal fade" id="modal-equipo" tabindex="-1" aria-labelledby="modal-equipo-titulo" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-secondary text-white justify-content-center"><h5 class="modal-title" id="modal-equipo-titulo">Añadir Nuevo Item</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button></div>
              <div class="modal-body">
                <form id="form-equipo" class="needs-validation" novalidate>
                  <input type="hidden" id="equipo_id" name="equipo_id">
                  <div class="row"><div class="col-12 mb-3"><label for="nombre_item" class="form-label">Nombre del Item <span class="text-danger">*</span></label><input type="text" class="form-control" id="nombre_item" name="nombre_item" required><div class="invalid-feedback">Por favor, ingresa un nombre.</div></div></div>
                  <div class="row"><div class="col-12 mb-3"><label for="descripcion" class="form-label">Descripción</label><textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea></div></div>
                  <div class="row"><div class="col-md-6 mb-3"><label for="departamento" class="form-label">Categoría <span class="text-danger">*</span></label><select class="form-select" id="departamento" name="departamento" required><option value="" disabled selected>Seleccione...</option><optgroup label="Venta (Tienda)"><option value="Suplementos">Suplementos</option><option value="Bebidas">Bebidas</option><option value="Accesorios de Venta">Accesorios (Venta)</option><option value="Lenceria">Lencería</option></optgroup><optgroup label="Equipamiento (Operaciones)"><option value="Maquinas">Máquinas</option><option value="Discos y Pesas">Discos y Pesas</option><option value="Mancuernas">Mancuernas</option><option value="Accesorios de Gym">Accesorios (Gimnasio)</option></optgroup></select><div class="invalid-feedback">Por favor, selecciona una categoría.</div></div><div class="col-md-6 mb-3" id="campo-estado"><label for="estado" class="form-label">Estado <span class="text-danger">*</span></label><select class="form-select" id="estado" name="estado" required><option value="Operativo">Operativo</option><option value="Mantenimiento">En Mantenimiento</option><option value="Averiado">Averiado</option><option value="Para la venta">Para la venta</option></select></div></div>
                  <div class="row"><div class="col-md-6 mb-3" id="campo-stock"><label for="stock" class="form-label">Stock</label><input type="number" class="form-control" id="stock" name="stock" min="0" value="1" required><div class="invalid-feedback">El stock debe ser >= 0.</div></div><div class="col-md-6 mb-3" id="campo-precio"><label for="precio" id="label-precio" class="form-label">Precio / Valor</label><input type="number" class="form-control" id="precio" name="precio" min="0" step="0.01" value="0"></div></div>
                  <div class="row"><div class="col-md-6 mb-3" id="campo-fecha"><label for="fecha_adquisicion" class="form-label">Fecha de Adquisición</label><input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion"></div></div>
                </form>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-danger" form="form-equipo" id="btn-guardar-equipo"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar</button></div>
            </div>
          </div>
        </div>

        <!-- Modal: Plan de Suscripción CRUD -->
        <div class="modal fade" id="modal-plan" tabindex="-1" aria-labelledby="modal-plan-titulo" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-secondary text-white justify-content-center"><h5 class="modal-title" id="modal-plan-titulo">Crear Nuevo Plan</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button></div>
              <div class="modal-body">
                <form id="form-plan" novalidate>
                  <input type="hidden" id="plan_id" name="plan_id">
                  <div class="mb-3"><label for="nombre_plan" class="form-label">Nombre del Plan <span class="text-danger">*</span></label><input type="text" class="form-control" id="nombre_plan" name="nombre_plan" required></div>
                  <div class="mb-3"><label for="descripcion_plan" class="form-label">Descripción</label><textarea class="form-control" id="descripcion_plan" name="descripcion_plan" rows="2"></textarea></div>
                  <div class="row"><div class="col-md-6 mb-3"><label for="precio_base_plan" class="form-label">Precio Base ($) <span class="text-danger">*</span></label><input type="number" class="form-control" id="precio_base_plan" name="precio_base_plan" min="0" step="0.01" required></div><div class="col-md-6 mb-3"><label for="duracion_dias_plan" class="form-label">Duración (días) <span class="text-danger">*</span></label><input type="number" class="form-control" id="duracion_dias_plan" name="duracion_dias_plan" min="1" required></div></div>
                  <div class="mb-3"><label for="estatus_plan" class="form-label">Estatus</label><select class="form-select" id="estatus_plan" name="estatus_plan"><option value="activo">Activo</option><option value="inactivo">Inactivo</option></select></div>
                </form>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-danger" form="form-plan">Guardar Plan</button></div>
            </div>
          </div>
        </div>

        <!-- Modal: Entrenador CRUD -->
        <div class="modal fade" id="modal-entrenador" tabindex="-1" aria-labelledby="modal-entrenador-titulo" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-secondary text-white justify-content-center"><h5 class="modal-title" id="modal-entrenador-titulo">Registrar Nuevo Entrenador</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button></div>
              <div class="modal-body">
                <form id="form-entrenador" enctype="multipart/form-data" novalidate>
                  <input type="hidden" id="entrenador_id" name="entrenador_id">
                  <div class="row"><div class="col-md-6 mb-3"><label for="nombre_completo_entrenador" class="form-label">Nombre Completo <span class="text-danger">*</span></label><input type="text" class="form-control" id="nombre_completo_entrenador" name="nombre_completo_entrenador" required></div><div class="col-md-6 mb-3"><label for="cedula_entrenador" class="form-label">Número de Cédula <span class="text-danger">*</span></label><input type="text" class="form-control" id="cedula_entrenador" name="cedula_entrenador" required></div></div>
                  <div class="row"><div class="col-md-6 mb-3"><label for="telefono_entrenador" class="form-label">Teléfono <span class="text-danger">*</span></label><input type="tel" class="form-control" id="telefono_entrenador" name="telefono_entrenador" required></div><div class="col-md-6 mb-3"><label for="email_entrenador" class="form-label">Email</label><input type="email" class="form-control" id="email_entrenador" name="email_entrenador"></div></div>
                  <div class="row"><div class="col-md-6 mb-3"><label for="especialidad_entrenador" class="form-label">Especialidad</label><input type="text" class="form-control" id="especialidad_entrenador" name="especialidad_entrenador"></div><div class="col-md-6 mb-3"><label for="costo_mensual_entrenador" class="form-label">Costo Mensualidad</label><input type="number" class="form-control" id="costo_mensual_entrenador" name="costo_mensual_entrenador" min="0" step="0.01" value="0"></div></div>
                  <div class="mb-3"><label for="foto_entrenador" class="form-label">Foto del Entrenador</label><input class="form-control" type="file" id="foto_entrenador" name="foto_entrenador" accept="image/*"></div>
                </form>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button><button type="submit" class="btn btn-danger" form="form-entrenador">Guardar Cambios</button></div>
            </div>
          </div>
        </div>
        
        <!-- Modal: Usuario CRUD -->
        <div class="modal fade" id="modal-usuario" tabindex="-1" aria-labelledby="modal-usuario-titulo" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-secondary text-white justify-content-center">
                <h5 class="modal-title" id="modal-usuario-titulo">Registrar Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button>
              </div>
              <div class="modal-body">
                <form id="form-usuario" novalidate>
                  <input type="hidden" id="usuario_id" name="id">
                  <div class="mb-3">
                    <label for="usuario_nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="usuario_nombre" name="nombre" required>
                  </div>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="usuario_cedula" class="form-label">Cédula</label>
                      <input type="text" class="form-control" id="usuario_cedula" name="cedula">
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="usuario_telefono" class="form-label">Teléfono</label>
                      <input type="text" class="form-control" id="usuario_telefono" name="telefono">
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="usuario_usuario" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="usuario_usuario" name="usuario" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="usuario_rol" class="form-label">Rol <span class="text-danger">*</span></label>
                      <select class="form-select" id="usuario_rol" name="rol" required>
                        <option value="recepcionista">Recepcionista</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="admin">Administrador</option>
                      </select>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="usuario_clave" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="usuario_clave" name="password" autocomplete="new-password">
                    <small class="form-text text-muted">Dejar en blanco para no cambiar la contraseña al editar.</small>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger" form="form-usuario">Guardar Usuario</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal: Realizar Venta -->
        <div class="modal fade" id="modal-realizar-venta" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fa-solid fa-cart-shopping me-2"></i>Punto de Venta para <span id="venta-nombre-miembro"></span></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-realizar-venta">
                            <input type="hidden" id="venta-miembro-id" name="miembro_id">
                            <div class="row align-items-end">
                                <div class="col-md-7 mb-3">
                                    <label for="venta-producto-select" class="form-label">Buscar Producto</label>
                                    {{-- Select2 para productos irá aquí --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
