<!-- Modal: Pagar / Asignar Suscripción (Versión Final 'Todo en Uno') -->
<div class="modal fade" id="modal-pago-suscripcion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Suscripción para <span id="pago-nombre-miembro"></span></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form-pago-suscripcion" novalidate>
          @csrf
          <input type="hidden" id="pago-miembro-id" name="miembro_id">
          
          <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="pago-plan-id" class="form-label">Plan <span class="text-danger">*</span></label>
                    <select class="form-select" id="pago-plan-id" name="plan_id" required></select>
                </div>
                <div class="mb-3">
                    <label for="pago-promocion-id" class="form-label">Promoción (Opcional)</label>
                    <select class="form-select" id="pago-promocion-id" name="promocion_id">
                        <option value="">Sin promoción</option>
                    </select>
                </div>
                 <div class="mb-3">
                    <label for="pago-entrenador-id" class="form-label">Entrenador (Opcional)</label>
                    <select class="form-select" id="pago-entrenador-id" name="entrenador_id">
                        <option value="">Sin entrenador</option>
                    </select>
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
          
          <div id="pago-detalles-plan" class="p-3 mb-3 bg-light rounded border" style="display: none;"></div>

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

<!-- Modal: Miembro CRUD -->
<div class="modal fade" id="modal-miembro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white justify-content-center">
        <h5 class="modal-title" id="modal-miembro-titulo">Registrar Nuevo Miembro</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button>
      </div>
      <div class="modal-body">
        <form id="form-miembro" enctype="multipart/form-data" novalidate>
          @csrf
          <input type="hidden" id="miembro_id" name="miembro_id">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="numero_cedula" class="form-label">Número de Cédula <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="numero_cedula" name="numero_cedula" required>
          </div>
          <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger" form="form-miembro" id="btn-guardar-miembro">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Inventario CRUD -->
<div class="modal fade" id="modal-equipo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white justify-content-center">
        <h5 class="modal-title" id="modal-equipo-titulo">Añadir Nuevo Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button>
      </div>
      <div class="modal-body">
        <form id="form-equipo" class="needs-validation" novalidate>
          @csrf
          <input type="hidden" id="equipo_id" name="item_id">
          <input type="hidden" id="tipo" name="tipo" value="Tienda">
          <div class="row">
            <div class="col-12 mb-3">
              <label for="nombre_item" class="form-label">Nombre del Item <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nombre_item" name="nombre_item" required>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="departamento" class="form-label">Categoría <span class="text-danger">*</span></label>
              <select class="form-select" id="departamento" name="departamento" required>
                <option value="" disabled selected>Seleccione...</option>
                <optgroup label="Venta (Tienda)">
                  <option value="Suplementos">Suplementos</option>
                  <option value="Bebidas">Bebidas</option>
                  <option value="Accesorios de Venta">Accesorios (Venta)</option>
                  <option value="Lenceria">Lencería</option>
                </optgroup>
                <optgroup label="Equipamiento (Operaciones)">
                  <option value="Maquinas">Máquinas</option>
                  <option value="Discos y Pesas">Discos y Pesas</option>
                  <option value="Mancuernas">Mancuernas</option>
                  <option value="Accesorios de Gym">Accesorios (Gimnasio)</option>
                </optgroup>
              </select>
            </div>
            <div class="col-md-6 mb-3" id="campo-estado">
              <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
              <select class="form-select" id="estado" name="estado" required>
                <option value="Operativo">Operativo</option>
                <option value="Mantenimiento">En Mantenimiento</option>
                <option value="Averiado">Averiado</option>
                <option value="Para la venta">Para la venta</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3" id="campo-stock">
              <label for="stock" class="form-label">Stock</label>
              <input type="number" class="form-control" id="stock" name="stock" min="0" value="1" required>
            </div>
            <div class="col-md-6 mb-3" id="campo-precio">
              <label for="precio" id="label-precio" class="form-label">Precio / Valor</label>
              <input type="number" class="form-control" id="precio" name="precio" min="0" step="0.01" value="0">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3" id="campo-fecha">
              <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición</label>
              <input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger" form="form-equipo" id="btn-guardar-equipo">
          <i class="fa-solid fa-floppy-disk me-2"></i>Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Plan de Suscripción CRUD -->
<div class="modal fade" id="modal-plan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white justify-content-center">
        <h5 class="modal-title" id="modal-plan-titulo">Crear Nuevo Plan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button>
      </div>
      <div class="modal-body">
        <form id="form-plan" novalidate>
          @csrf
          <input type="hidden" id="plan_id" name="plan_id">
          <div class="mb-3">
            <label for="nombre_plan" class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nombre_plan" name="nombre_plan" required>
          </div>
          <div class="mb-3">
            <label for="descripcion_plan" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion_plan" name="descripcion_plan" rows="2"></textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="precio_base_plan" class="form-label">Precio Base ($) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="precio_base_plan" name="precio_base_plan" min="0" step="0.01" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="duracion_dias_plan" class="form-label">Duración (días) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="duracion_dias_plan" name="duracion_dias_plan" min="1" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="estatus_plan" class="form-label">Estatus</label>
            <select class="form-select" id="estatus_plan" name="estatus_plan">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger" form="form-plan">Guardar Plan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Entrenador CRUD -->
<div class="modal fade" id="modal-entrenador" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white justify-content-center">
        <h5 class="modal-title" id="modal-entrenador-titulo">Registrar Nuevo Entrenador</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="position: absolute; right: 1rem;"></button>
      </div>
      <div class="modal-body">
        <form id="form-entrenador" enctype="multipart/form-data" novalidate>
          @csrf
          <input type="hidden" id="entrenador_id" name="entrenador_id">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="nombre_completo_entrenador" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="nombre_completo_entrenador" name="nombre_completo" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="cedula_entrenador" class="form-label">Número de Cédula <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="cedula_entrenador" name="numero_cedula" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="telefono_entrenador" class="form-label">Teléfono <span class="text-danger">*</span></label>
              <input type="tel" class="form-control" id="telefono_entrenador" name="telefono" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="email_entrenador" class="form-label">Email</label>
              <input type="email" class="form-control" id="email_entrenador" name="email">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="especialidad_entrenador" class="form-label">Especialidad</label>
              <input type="text" class="form-control" id="especialidad_entrenador" name="especialidad">
            </div>
            <div class="col-md-6 mb-3">
              <label for="costo_mensual_entrenador" class="form-label">Costo Mensualidad</label>
              <input type="number" class="form-control" id="costo_mensual_entrenador" name="costo_mensual" min="0" step="0.01" value="0">
            </div>
          </div>
          <div class="mb-3">
            <label for="foto_entrenador" class="form-label">Foto del Entrenador</label>
            <input class="form-control" type="file" id="foto_entrenador" name="foto_entrenador" accept="image/*">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger" form="form-entrenador">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Realizar Venta (Punto de Venta con Buscador) -->
<div class="modal fade" id="modal-realizar-venta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa-solid fa-cart-shopping me-2"></i>Punto de Venta para <span id="venta-nombre-miembro"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-realizar-venta">
                    @csrf
                    <input type="hidden" id="venta-miembro-id" name="miembro_id">
                    <div class="row align-items-end">
                        <div class="col-md-7 mb-3">
                            <label for="venta-producto-select" class="form-label">Buscar Producto</label>
                            <select class="form-select" id="venta-producto-select" name="producto_id" data-placeholder="Escribe para buscar un producto..."></select>
                        </div>
                        <div class="col-md-2 mb-3">
                             <label for="venta-cantidad" class="form-label">Cantidad</label>
                             <input type="number" class="form-control" id="venta-cantidad" value="1" min="1">
                        </div>
                        <div class="col-md-3 mb-3">
                            <button type="button" id="btn-agregar-producto" class="btn btn-primary w-100">
                                <i class="fa-solid fa-cart-plus me-2"></i>Añadir al Carrito
                            </button>
                        </div>
                    </div>
                    <hr>
                    <h6>Carrito de Compras</h6>
                    <div class="table-responsive" style="max-height: 250px; min-height: 100px;">
                        <table class="table table-sm">
                            <thead><tr><th>Producto</th><th>Cant.</th><th>P/U</th><th>Subtotal</th><th></th></tr></thead>
                            <tbody id="venta-carrito-body">
                                <tr><td colspan="5" class="text-center text-muted">El carrito está vacío</td></tr>
                            </tbody>
                        </table>
                    </div>
                     <div class="alert alert-success mt-3 text-end">
                        <h4>Total: <strong id="venta-total-usd">$0.00</strong> / <strong id="venta-total-bs">Bs. 0,00</strong></h4>
                     </div>

                     <!-- Método de Pago -->
                     <hr>
                     <h6>Método de Pago</h6>
                     <div class="row">
                         <div class="col-md-6 mb-3">
                             <label for="venta-metodo-pago" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                             <select class="form-select" id="venta-metodo-pago" name="metodo_pago" required>
                                 <option value="" disabled selected>Seleccione...</option>
                                 <option value="Divisa ($)">Divisa ($)</option>
                                 <option value="Punto de Venta (Bs.)">Punto de Venta (Bs.)</option>
                                 <option value="Pago Móvil (Bs.)">Pago Móvil (Bs.)</option>
                             </select>
                         </div>
                         <div class="col-md-6 mb-3" id="campo-referencia-venta" style="display: none;">
                             <label for="venta-referencia" class="form-label">Número de Referencia <span class="text-danger">*</span></label>
                             <input type="text" class="form-control" id="venta-referencia" name="referencia_pago" placeholder="Ingrese el número de referencia">
                         </div>
                     </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" form="form-realizar-venta">Finalizar Venta</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Usuario CRUD -->
<div class="modal fade" id="modal-usuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="modal-usuario-titulo">Registrar Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="form-usuario" novalidate>
          @csrf
          <input type="hidden" id="usuario_id" name="usuario_id">
          <div class="mb-3">
            <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="usuario_nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Cédula <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="usuario_cedula" name="cedula" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="usuario_telefono" name="telefono">
          </div>
          <hr>
          <div class="mb-3">
            <label class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="usuario_usuario" name="usuario" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="usuario_clave" name="clave" placeholder="Dejar en blanco para no cambiar">
          </div>
          <div class="mb-3">
            <label class="form-label">Rol <span class="text-danger">*</span></label>
            <select class="form-select" id="usuario_rol" name="rol" required>
              <option value="recepcionista">Recepcionista</option>
              <option value="supervisor">Supervisor</option>
              <option value="admin">Administrador</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-danger" form="form-usuario">Guardar</button>
      </div>
    </div>
  </div>
</div>
