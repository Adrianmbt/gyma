// js/miembros.js (Adaptado para Laravel)
$(document).ready(function() {
    // ---- Variables y Constantes ----
    const modalEl = document.getElementById('modal-miembro');
    const modalInstance = new bootstrap.Modal(modalEl);
    const form = $('#form-miembro');
    const modalTitle = $('#modal-miembro-titulo');
    const miembroIdInput = $('#miembro_id');
    const tablaMiembrosContainer = $('#lista-miembros-container');

    // ---- Inyectar y configurar DataTable ----
    tablaMiembrosContainer.html(`
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
    `);

    const dataTable = $('#tabla-miembros').DataTable({
        processing: true,
        serverSide: true,
        // Adaptado para Laravel API
        ajax: {
            url: "/api/miembros",
            type: "GET" // apiResource usa GET para index
        },
        columns: [
            { 
                data: "ruta_foto", 
                render: function(data) {
                    const fotoSrc = data ? data : defaultImageUrl;
                    return `<img src="${fotoSrc}?t=${new Date().getTime()}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Foto Miembro">`;
                },
                orderable: false,
                searchable: false
            },
            { data: "nombre" },
            { data: "numero_cedula" },
            { data: "telefono" },
            { 
                data: "estatus_suscripcion",
                render: function(data) {
                    let badgeClass = 'bg-secondary';
                    let estatusTexto = data || 'Sin Plan';
                    switch (data) {
                        case 'Activo': badgeClass = 'bg-success'; break;
                        case 'Por Vencer': badgeClass = 'bg-warning text-dark'; break;
                        case 'Vencida': badgeClass = 'bg-danger'; break;
                        case 'Sin Suscripción': badgeClass = 'bg-info text-dark'; break;
                        case 'Vetado': badgeClass = 'bg-dark'; break;
                    }
                    return `<span class="badge ${badgeClass}">${estatusTexto}</span>`;
                }
            },
            { 
                data: "id", 
                render: function(data, type, row) {
                    let botones = `
                        <a href="/reportes/historial-pagos/${data}" target="_blank" class="btn btn-sm btn-secondary" title="Historial de Pagos PDF"><i class="fa-solid fa-file-pdf"></i></a>
                        <button class="btn btn-sm btn-info btn-edit" data-id="${data}" title="Editar Miembro"><i class="fa-solid fa-pencil"></i></button> 
                        <button class="btn btn-sm btn-dark btn-status" data-id="${data}" data-nombre="${row.nombre}" title="Suspender / Cambiar Estatus"><i class="fa-solid fa-user-gear"></i></button>
                    `;
                    if (USER_ROLE === 'admin' || USER_ROLE === 'supervisor') {
                        botones += ` <button class="btn btn-sm btn-danger btn-delete" data-id="${data}" title="Eliminar Miembro"><i class="fa-solid fa-trash"></i></button>`;
                    }
                    return botones;
                }, 
                orderable: false, 
                searchable: false 
            }
        ],
        language: { url: dataTablesLanguageUrl },
        responsive: true,
        autoWidth: false
    });

    // ---- Lógica del Modal (Añadir/Editar) ----
    $('#btn-nuevo-miembro').click(function() {
        form[0].reset();
        miembroIdInput.val('');
        modalTitle.text('Registrar Nuevo Miembro');
        modalInstance.show();
    });

    $('#tabla-miembros').on('click', '.btn-edit', function() {
        const miembroId = $(this).data('id');
        // Adaptado para Laravel API
        $.get(`/api/miembros/${miembroId}`, function(miembro) {
            form[0].reset();
            modalTitle.text('Editar Miembro');
            miembroIdInput.val(miembro.id);
            $('#nombre').val(miembro.nombre);
            $('#telefono').val(miembro.telefono);
            $('#numero_cedula').val(miembro.numero_cedula);
            $('#fecha_nacimiento').val(miembro.fecha_nacimiento);
            modalInstance.show();
        }).fail(() => Swal.fire('Error', 'No se pudo obtener la información del miembro.', 'error'));
    });

    // ---- Lógica de CRUD ----
    form.submit(function(e) {
        e.preventDefault();
        const miembroId = miembroIdInput.val();
        const url = miembroId ? `/api/miembros/${miembroId}` : '/api/miembros';
        // Para Laravel, cuando se usa FormData con PUT/PATCH, se debe "engañar" al servidor.
        // Se envía como POST y se añade un campo _method.
        const formData = new FormData(this);
        if (miembroId) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST', // Siempre POST para FormData con _method
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                modalInstance.hide();
                Swal.fire('¡Éxito!', response.message, 'success');
                dataTable.ajax.reload(null, false);
            },
            error: function(jqXHR) {
                const errors = jqXHR.responseJSON?.errors;
                let errorMsg = 'Ocurrió un error inesperado.';
                if (errors) {
                    errorMsg = Object.values(errors).map(e => e.join('\n')).join('<br>');
                }
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    });

    // Cambiar ESTATUS MANUAL (para vetar principalmente)
    $('#tabla-miembros').on('click', '.btn-status', function() {
        const miembroId = $(this).data('id');
        const nombreMiembro = $(this).data('nombre');

        Swal.fire({
            title: `Cambiar estatus manual de ${nombreMiembro}`,
            input: 'select',
            inputOptions: {
                'activo': 'Activo (Restaurar)',
                'inactivo': 'Inactivo',
                'vetado': 'Vetado'
            },
            inputPlaceholder: 'Selecciona un estatus',
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Adaptado para Laravel API
                $.ajax({
                    url: `/api/miembros/${miembroId}/status`,
                    type: 'PATCH',
                    data: { estatus: result.value },
                    success: function(response) {
                        Swal.fire('¡Actualizado!', response.message, 'success');
                        dataTable.ajax.reload(null, false);
                    },
                    error: (jqXHR) => Swal.fire('Error', jqXHR.responseJSON?.message || 'No se pudo actualizar.', 'error')
                });
            }
        });
    });

    // ELIMINAR
    $('#tabla-miembros').on('click', '.btn-delete', function() {
        const miembroId = $(this).data('id');
        Swal.fire({
            title: '¿Estás completamente seguro?',
            text: "Esta acción eliminará al miembro PERMANENTEMENTE y no se puede revertir.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, ¡eliminar!',
        }).then((result) => {
            if (result.isConfirmed) {
                // Adaptado para Laravel API
                $.ajax({
                    url: `/api/miembros/${miembroId}`,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire('¡Eliminado!', response.message, 'success');
                        dataTable.ajax.reload(null, false);
                    },
                    error: (jqXHR) => Swal.fire('Error', jqXHR.responseJSON?.message || 'No se pudo eliminar.', 'error')
                });
            }
        });
    });
});
