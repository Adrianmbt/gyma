$(document).ready(function() {
    // ---- Selectores y Variables Globales ----
    const formBusqueda = $('#form-buscar-miembro');
    const inputCedula = $('#cedula-buscar');
    const tablaRegistrosContainer = $('#registros-recientes-container');
    let miembroActual = null;
    let datosSuscripcion = { planes: [], promociones: [], entrenador_asignado: null, entrenadores_disponibles: [] };
    let carritoDeVenta = [];
    let proximaModal = null;

    // ---- Instancias de Modales de Bootstrap ----
    const modalFichaInstance = new bootstrap.Modal(document.getElementById('modal-ficha-miembro'));
    const modalPagoInstance = new bootstrap.Modal(document.getElementById('modal-pago-suscripcion'));
    const modalVentaInstance = new bootstrap.Modal(document.getElementById('modal-realizar-venta'));
    
    // ---- Manejador de Eventos para Transición Limpia entre modales ----
    $(document.getElementById('modal-ficha-miembro')).on('hidden.bs.modal', function () {
        if (proximaModal) {
            proximaModal.show();
        }
        proximaModal = null; // Limpiar para el próximo uso
    });

    // ===================================================================
    // BÚSQUEDA DE MIEMBRO
    // ===================================================================
    formBusqueda.submit(function(e) {
        e.preventDefault();
        const cedula = inputCedula.val().trim();
        if (!cedula) {
            Swal.fire('Atención', 'Por favor, ingresa un número de cédula.', 'warning');
            return;
        }
        // Adaptado para Laravel API
        $.get(`/api/miembros/buscar/${cedula}`, function(data) {
            miembroActual = data;
            llenarFicha(miembroActual);
            modalFichaInstance.show();
        }).fail(function(jqXHR) {
            const errorMsg = jqXHR.responseJSON?.message || 'Cédula no registrada.';
            Swal.fire({
                icon: 'error',
                title: 'Miembro no encontrado',
                text: errorMsg,
                footer: '<a href="#" id="ir-a-registrar">¿Deseas registrarlo ahora?</a>'
            });
        });
    });

    function llenarFicha(miembro) {
        // Usamos el helper asset() de Laravel para las rutas
        $('#ficha-foto').attr('src', miembro.ruta_foto ? `storage/${miembro.ruta_foto.replace('public/', '')}` : defaultImageUrl);
        $('#ficha-nombre').text(miembro.nombre);
        $('#ficha-cedula').text(miembro.numero_cedula);
        $('#ficha-telefono').text(miembro.telefono);
        $('#ficha-nacimiento').text(new Date(miembro.fecha_nacimiento + 'T00:00:00').toLocaleDateString('es-VE'));
        $('#ficha-edad').text(`${miembro.edad} años`);
        $('#ficha-membresia-nombre').text(miembro.nombre_membresia || 'N/A');
        $('#ficha-membresia-estado').text(miembro.estatus_suscripcion || 'N/A');
        $('#ficha-membresia-vencimiento').text(miembro.fecha_fin_suscripcion ? new Date(miembro.fecha_fin_suscripcion).toLocaleDateString('es-VE') : 'N/A');
        const estatusEl = $('#ficha-estatus');
        estatusEl.text(miembro.estatus.charAt(0).toUpperCase() + miembro.estatus.slice(1))
            .removeClass('bg-success bg-danger')
            .addClass(miembro.estatus === 'activo' ? 'bg-success' : 'bg-danger');
    }

    $(document).on('click', '#ir-a-registrar', function(e) {
        e.preventDefault();
        Swal.close();
        $('#miembros-tab').tab('show');
        $('#btn-nuevo-miembro').click(); // Abrir modal de registro
    });

    // ===================================================================
    // DATATABLE DE REGISTROS RECIENTES
    // ===================================================================
    const tablaRegistros = tablaRegistrosContainer.html('<table id="tabla-registros-recientes" class="table table-striped table-hover" style="width:100%"><thead class="table-dark"><tr><th>Fecha</th><th>Miembro</th><th>Tipo</th><th>Detalles</th><th>Monto ($)</th><th>Ref.</th><th>Acciones</th></tr></thead></table>').find('table').DataTable({
        processing: true,
        serverSide: true,
        // Adaptado para Laravel API
        ajax: {
            url: "/api/registros",
            type: "GET" 
        },
        columns: [
            { data: "fecha", render: data => data ? new Date(data).toLocaleString('es-VE') : 'N/A' },
            { data: "miembro_nombre" },
            { data: "tipo", render: data => `<span class="badge ${data === 'Suscripción' ? 'bg-primary' : 'bg-info'}">${data}</span>` },
            { data: "detalles", width: "30%" },
            { data: "monto", render: data => data ? `$${parseFloat(data).toFixed(2)}` : '$0.00' },
            { data: "referencia" },
            { 
                data: null, 
                render: function(data, type, row) {
                    let botones = '';
                    // La edición y eliminación se manejarán en sus respectivas pestañas ahora.
                    if (row.tipo === 'Venta Tienda') {
                        // Adaptar la ruta del reporte
                        botones += `<a href="/reportes/factura_venta/${row.id}" target="_blank" class="btn btn-sm btn-secondary" title="Ver Factura PDF"><i class="fa-solid fa-receipt"></i></a>`;
                    }
                    return botones;
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, "desc"]],
        language: { url: dataTablesLanguageUrl }
    });
    
    // ===================================================================
    // LÓGICA MODAL 1: SUSCRIPCIÓN
    // ===================================================================
    $('#btn-pagar-suscripcion').click(function() {
        if (!miembroActual) return;

        $('#form-pago-suscripcion')[0].reset();
        $('#pago-nombre-miembro').text(miembroActual.nombre);
        $('#pago-miembro-id').val(miembroActual.id);
        $('#campo-referencia-pago, #pago-entrenador-detalles, #pago-detalles-plan').hide();
        $('#pago-total-usd').text('$0.00');
        $('#pago-total-bs').text('Bs. 0,00');

        // Adaptado para Laravel API
        $.get(`/api/pagos/datos-suscripcion/${miembroActual.id}`)
            .done(function(data) {
                datosSuscripcion = data;
                
                const selectPlanes = $('#pago-plan-id').empty().append('<option value="">Seleccione un plan...</option>');
                datosSuscripcion.planes.forEach(plan => selectPlanes.append(new Option(`${plan.nombre_plan} ($${parseFloat(plan.precio_base).toFixed(2)})`, plan.id)));

                const selectPromos = $('#pago-promocion-id').empty().append('<option value="0">Sin promoción</option>');
                // Las promociones ahora son parte de los planes, esto puede necesitar ajuste
                // Por ahora, lo dejamos así si la API devuelve promociones separadas.
                if(datosSuscripcion.promociones) {
                    datosSuscripcion.promociones.forEach(promo => selectPromos.append(new Option(promo.nombre_promo, promo.id)));
                }

                const selectEntrenadores = $('#pago-entrenador-id').empty().append('<option value="0">Sin entrenador</option>');
                datosSuscripcion.entrenadores_disponibles.forEach(ent => selectEntrenadores.append(new Option(`${ent.nombre_completo} (+$${parseFloat(ent.costo_mensual).toFixed(2)})`, ent.id)));
                
                if (datosSuscripcion.entrenador_asignado) {
                    selectEntrenadores.val(datosSuscripcion.entrenador_asignado.id);
                }
                
                calcularTotalSuscripcion();
                proximaModal = modalPagoInstance;
                modalFichaInstance.hide();
            })
            .fail(() => Swal.fire('Error de Red', 'No se pudo contactar a la API.', 'error'));
    });

    $('#pago-plan-id, #pago-promocion-id, #pago-entrenador-id').change(calcularTotalSuscripcion);

    $('#pago-metodo-pago').change(function(){
        const campoRef = $('#campo-referencia-pago');
        if ($(this).val() === 'Punto de Venta (Bs.)' || $(this).val() === 'Pago Móvil (Bs.)') {
            campoRef.slideDown().find('input').prop('required', true);
        } else {
            campoRef.slideUp().find('input').prop('required', false);
        }
    });

    function calcularTotalSuscripcion() {
        const detallesPlanEl = $('#pago-detalles-plan');
        const detallesEntrenadorEl = $('#pago-entrenador-detalles');
        detallesPlanEl.hide().empty();
        $('#pago-total-usd').text('$0.00');
        $('#pago-total-bs').text('Bs. 0,00');

        const planSeleccionado = datosSuscripcion.planes.find(p => p.id == $('#pago-plan-id').val());
        if (!planSeleccionado) {
            detallesEntrenadorEl.slideUp();
            return;
        }

        let precioBase = parseFloat(planSeleccionado.precio_base);
        let precioConPromo = precioBase;
        let descuentoTexto = '$0.00';
        let costoEntrenador = 0;

        // Lógica de promoción puede cambiar si se integra en planes
        const promoSeleccionada = datosSuscripcion.promociones?.find(p => p.id == $('#pago-promocion-id').val());
        if (promoSeleccionada) {
            precioConPromo = promoSeleccionada.tipo_descuento === 'porcentaje' ? precioBase - (precioBase * parseFloat(promoSeleccionada.valor_descuento) / 100) : parseFloat(promoSeleccionada.valor_descuento);
            descuentoTexto = `-$${(precioBase - precioConPromo).toFixed(2)}`;
        }

        const entrenadorSeleccionado = datosSuscripcion.entrenadores_disponibles.find(e => e.id == $('#pago-entrenador-id').val());
        if (entrenadorSeleccionado) {
            costoEntrenador = parseFloat(entrenadorSeleccionado.costo_mensual);
            $('#detalle-pago-entrenador-especialidad').text(entrenadorSeleccionado.especialidad || 'N/A');
            detallesEntrenadorEl.slideDown();
        } else {
            detallesEntrenadorEl.slideUp();
        }

        const totalAPagar = precioConPromo + costoEntrenador;
        let htmlDetalles = `<div class="d-flex justify-content-between"><span>Plan:</span><span class="fw-bold">$${precioBase.toFixed(2)}</span></div><div class="d-flex justify-content-between"><span>Descuento:</span><span class="fw-bold text-danger">${descuentoTexto}</span></div>`;
        if (costoEntrenador > 0) { htmlDetalles += `<div class="d-flex justify-content-between"><span>Entrenador:</span><span class="fw-bold text-success">+$${costoEntrenador.toFixed(2)}</span></div>`; }
        htmlDetalles += `<hr class="my-1"><div class="d-flex justify-content-between"><span>Duración:</span><span class="fw-bold">${planSeleccionado.duracion_dias} días</span></div>`;
        
        detallesPlanEl.html(htmlDetalles).show();
        $('#pago-total-usd').text(`$${totalAPagar.toFixed(2)}`);
        $('#pago-total-bs').text(`Bs. ${(totalAPagar * (window.tasaBCV || 0)).toFixed(2)}`);
    }

    $('#form-pago-suscripcion').submit(function(e) {
        e.preventDefault();
        if (!this.checkValidity()) { this.reportValidity(); return; }
        
        const formData = $(this).serializeArray();
        formData.push({name: 'monto_pagado', value: parseFloat($('#pago-total-usd').text().replace('$', ''))});
        
        // Adaptado para Laravel API
        $.post('/api/pagos/suscripcion', $.param(formData), function(res) {
            Swal.fire('¡Éxito!', res.message, 'success');
            modalPagoInstance.hide();
            tablaRegistros.ajax.reload();
            if ($.fn.DataTable.isDataTable('#tabla-miembros')) $('#tabla-miembros').DataTable().ajax.reload(null, false);
            if (typeof cargarDatosDashboardAdmin === 'function') cargarDatosDashboardAdmin(); 
        }).fail(jqXHR => Swal.fire('Error', jqXHR.responseJSON?.message || 'No se pudo registrar.', 'error'));
    });

    // ===================================================================
    // LÓGICA MODAL 2: VENTA
    // ===================================================================
    $('#btn-realizar-venta').click(function() {
        if (!miembroActual) return;
        carritoDeVenta = [];
        // La actualización visual del carrito se hará en una función separada
        // actualizarCarritoVisual(); 
        $('#venta-nombre-miembro').text(miembroActual.nombre);
        $('#venta-miembro-id').val(miembroActual.id);
        
        // Inicializar Select2 para productos
        $('#venta-producto-select').val(null).trigger('change').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modal-realizar-venta'),
            placeholder: 'Escribe para buscar un producto...',
            ajax: {
                // Adaptado para Laravel API
                url: '/api/ventas/productos',
                dataType: 'json',
                delay: 250,
                data: params => ({ search: params.term }),
                processResults: response => ({
                    results: response.map(item => ({
                        id: item.id,
                        text: `${item.nombre_item} - $${parseFloat(item.precio).toFixed(2)} (Stock: ${item.stock})`,
                        stock: parseInt(item.stock, 10),
                        precio: parseFloat(item.precio)
                    }))
                }),
                cache: true
            }
        });
        proximaModal = modalVentaInstance;
        modalFichaInstance.hide();
    });

    // La lógica de agregar al carrito, actualizar y finalizar venta necesita ser migrada
    // a un nuevo modal de ventas que aún no hemos diseñado completamente en el HTML.
    // Por ahora, esta funcionalidad quedará pendiente.
});
