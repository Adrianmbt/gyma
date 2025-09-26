// js/entrenadores.js (Adaptado para Laravel)
$(document).ready(function() {
    // ---- Variables y Constantes ----
    const modalEl = document.getElementById('modal-entrenador');
    const modalInstance = new bootstrap.Modal(modalEl);
    const form = $('#form-entrenador');
    const modalTitle = $('#modal-entrenador-titulo');
    const entrenadorIdInput = $('#entrenador_id');
    const tablaEntrenadoresContainer = $('#lista-entrenadores-container');

    // ---- DataTable Initialization ----
    tablaEntrenadoresContainer.html('<table id="tabla-entrenadores" class="table table-striped table-hover" style="width:100%"><thead class="table-dark"><tr><th>Foto</th><th>Nombre</th><th>Especialidad</th><th>Teléfono</th><th>Costo Mensual</th><th>Estatus</th><th>Acciones</th></tr></thead><tbody></tbody></table>');

    const dataTable = $('#tabla-entrenadores').DataTable({
        processing: true,
        serverSide: true,
        // Adaptado para Laravel API
        ajax: {
            url: "/api/entrenadores",
            type: "GET"
        },
        columns: [
            { 
                data: "ruta_foto", 
                render: function(data) {
                    const fotoSrc = data ? data : defaultImageUrl;
                    return `<img src="${fotoSrc}?t=${new Date().getTime()}" alt="Foto" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">`;
                },
                orderable: false,
                searchable: false
            },
            { data: "nombre_completo" },
            { data: "especialidad" },
            { data: "telefono" },
            { data: "costo_mensual", render: $.fn.dataTable.render.number(',', '.', 2, '$') },
            { 
                data: "estatus", 
                render: function(data) {
                    const badgeClass = data === 'activo' ? 'bg-success' : 'bg-secondary';
                    return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                }
            },
            { 
                data: "id", 
                render: function(data, type, row) {
                    if (USER_ROLE === 'recepcionista') {
                        return 'N/A';
                    }
                    return `
                        <button class="btn btn-sm btn-info btn-edit" data-id="${data}" title="Editar Entrenador"><i class="fa-solid fa-pencil"></i></button>
                        <button class="btn btn-sm btn-dark btn-status" data-id="${data}" data-nombre="${row.nombre_completo}" title="Cambiar Estatus"><i class="fa-solid fa-toggle-on"></i></button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${data}" title="Eliminar Entrenador"><i class="fa-solid fa-trash"></i></button>
                    `;
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
    $('#btn-abrir-modal-entrenador').click(function() {
        form[0].reset();
        entrenadorIdInput.val('');
        modalTitle.text('Registrar Nuevo Entrenador');
        modalInstance.show();
    });

    $('#tabla-entrenadores').on('click', '.btn-edit', function() {
        const entrenadorId = $(this).data('id');
        // Adaptado para Laravel API
        $.get(`/api/entrenadores/${entrenadorId}`, function(entrenador) {
            form[0].reset();
            modalTitle.text('Editar Entrenador');
            entrenadorIdInput.val(entrenador.id);
            $('#nombre_completo_entrenador').val(entrenador.nombre_completo);
            $('#cedula_entrenador').val(entrenador.numero_cedula);
            $('#telefono_entrenador').val(entrenador.telefono);
            $('#email_entrenador').val(entrenador.email);
            $('#especialidad_entrenador').val(entrenador.especialidad);
            $('#costo_mensual_entrenador').val(entrenador.costo_mensual);
            modalInstance.show();
        }).fail(() => Swal.fire('Error', 'No se pudo obtener la información del entrenador.', 'error'));
    });

    // ---- Lógica de CRUD ----
    form.submit(function(e) {
        e.preventDefault();
        const entrenadorId = entrenadorIdInput.val();
        const url = entrenadorId ? `/api/entrenadores/${entrenadorId}` : '/api/entrenadores';
        const formData = new FormData(this);
        if (entrenadorId) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
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

    $('#tabla-entrenadores').on('click', '.btn-status', function() {
        const entrenadorId = $(this).data('id');
        const nombre = $(this).data('nombre');

        Swal.fire({
            title: `Cambiar estatus de ${nombre}`,
            input: 'select',
            inputOptions: { 'activo': 'Activo', 'inactivo': 'Inactivo' },
            showCancelButton: true,
            confirmButtonText: 'Actualizar',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Adaptado para Laravel API
                $.ajax({
                    url: `/api/entrenadores/${entrenadorId}/status`,
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

    $('#tabla-entrenadores').on('click', '.btn-delete', function() {
        const entrenadorId = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará al entrenador permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, ¡eliminar!',
        }).then((result) => {
            if (result.isConfirmed) {
                // Adaptado para Laravel API
                $.ajax({
                    url: `/api/entrenadores/${entrenadorId}`,
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
