// js/usuarios.js (Adaptado para Laravel)
$(document).ready(function() {
    // ---- Selectores y Variables ----
    const modalEl = document.getElementById('modal-usuario');
    const modalInstance = new bootstrap.Modal(modalEl);
    const form = $('#form-usuario');
    const modalTitle = $('#modal-usuario-titulo');
    const userIdInput = $('#usuario_id');
    const tablaUsuarios = $('#tabla-usuarios');

    // ---- DataTable Initialization ----
    // Solo inicializar si el rol es 'admin'
    if (USER_ROLE === 'admin' && tablaUsuarios.length) {
        const dataTable = tablaUsuarios.DataTable({
            processing: true,
            serverSide: true,
            // Adaptado para Laravel API
            ajax: {
                url: "/api/usuarios",
                type: "GET"
            },
            columns: [
                { data: "nombre" },
                { data: "usuario" },
                { data: "cedula" },
                { data: "telefono" },
                { 
                    data: "rol", 
                    render: data => `<span class="badge bg-info text-dark">${data.charAt(0).toUpperCase() + data.slice(1)}</span>` 
                },
                { 
                    data: "id",
                    render: function(data, type, row) {
                        // Evitar que el admin se elimine a sí mismo
                        if (row.usuario === 'admin' || row.usuario === 'Admin') {
                             return `<button class="btn btn-sm btn-info btn-edit-usuario" data-id="${data}" title="Editar"><i class="fa fa-pencil"></i></button>`;
                        }
                        return `<button class="btn btn-sm btn-info btn-edit-usuario" data-id="${data}" title="Editar"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-usuario" data-id="${data}" title="Eliminar"><i class="fa fa-trash"></i></button>`;
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
        $('#btn-nuevo-usuario').click(function() {
            form[0].reset();
            userIdInput.val('');
            $('#usuario_clave').attr('placeholder', 'Contraseña (obligatoria)').prop('required', true);
            modalTitle.text('Registrar Nuevo Usuario');
            modalInstance.show();
        });

        tablaUsuarios.on('click', '.btn-edit-usuario', function() {
            const userId = $(this).data('id');
            // Adaptado para Laravel API
            $.get(`/api/usuarios/${userId}`, function(user) {
                form[0].reset();
                modalTitle.text('Editar Usuario');
                userIdInput.val(user.id);
                $('#usuario_nombre').val(user.nombre);
                $('#usuario_cedula').val(user.cedula);
                $('#usuario_telefono').val(user.telefono);
                $('#usuario_usuario').val(user.usuario);
                $('#usuario_rol').val(user.rol);
                $('#usuario_clave').attr('placeholder', 'Dejar en blanco para no cambiar').prop('required', false);
                modalInstance.show();
            }).fail(() => Swal.fire('Error', 'No se pudo obtener la información del usuario.', 'error'));
        });

        // ---- Lógica de CRUD (Crear/Actualizar) ----
        form.submit(function(e) {
            e.preventDefault();
            const userId = userIdInput.val();
            const url = userId ? `/api/usuarios/${userId}` : '/api/usuarios';
            const method = userId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
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

        // ---- Lógica de Eliminación ----
        tablaUsuarios.on('click', '.btn-delete-usuario', function() {
            const userId = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡eliminar!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Adaptado para Laravel API
                    $.ajax({
                        url: `/api/usuarios/${userId}`,
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
    }
});
