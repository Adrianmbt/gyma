// js/inventario.js (Adaptado para Laravel)
$(document).ready(function() {
    // ---- Variables y Constantes del DOM ----
    const modalEl = document.getElementById('modal-equipo');
    const modalInstance = new bootstrap.Modal(modalEl);
    const form = $('#form-equipo');
    const modalTitle = $('#modal-equipo-titulo');
    const itemIdInput = $('#equipo_id');
    const tablaInventario = $('#tabla-inventario');
    const labelPrecio = $('#label-precio');
    const selectDepartamento = $('#departamento');

    // ---- DataTable Initialization ----
    const dataTable = tablaInventario.DataTable({
        processing: true,
        serverSide: true,
        // Adaptado para Laravel API
        ajax: {
            url: "/api/inventario",
            type: "GET"
        },
        columns: [
            { data: "codigo_item" },
            { data: "nombre_item" },
            { 
                data: "descripcion", 
                render: data => data && data.length > 40 ? `<span title="${data}">${data.substring(0, 40)}...</span>` : (data || 'N/A')
            },
            { data: "departamento" },
            { data: "estado" },
            { data: "precio", render: $.fn.dataTable.render.number(',', '.', 2, '$') },
            { data: "stock" },
            { 
                data: "id", 
                render: function(data) {
                    if (USER_ROLE === 'admin' || USER_ROLE === 'supervisor') {
                        return `<button class="btn btn-sm btn-info btn-edit-item" data-id="${data}" title="Editar"><i class="fa-solid fa-pencil"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-item" data-id="${data}" title="Eliminar"><i class="fa-solid fa-trash"></i></button>`;
                    }
                    return 'N/A';
                }, 
                orderable: false, 
                searchable: false 
            }
        ],
        language: { url: dataTablesLanguageUrl },
        responsive: true,
        autoWidth: false
    });

    // ---- Lógica de UI del Formulario ----
    function toggleFormFields() {
        const categoria = selectDepartamento.find('option:selected');
        const tipoGrupo = categoria.parent().attr('label');

        if (tipoGrupo?.includes('Venta')) {
            labelPrecio.text('Precio de Venta');
            $('#campo-precio, #campo-stock').show();
            $('#campo-estado, #campo-fecha').hide();
            $('#estado').val('Para la venta');
        } else if (tipoGrupo?.includes('Equipamiento')) {
            labelPrecio.text('Valor del Equipo');
            $('#campo-precio, #campo-stock, #campo-estado, #campo-fecha').show();
            if ($('#estado').val() === 'Para la venta') {
                $('#estado').val('Operativo');
            }
        } else {
            $('#campo-precio, #campo-estado, #campo-fecha').hide();
        }
    }

    selectDepartamento.change(toggleFormFields);

    // ---- Lógica del Modal (Añadir/Editar) ----
    $('#btn-nuevo-item').click(function() {
        form[0].reset();
        itemIdInput.val('');
        modalTitle.text('Añadir Nuevo Item al Inventario');
        toggleFormFields();
        modalInstance.show();
    });

    tablaInventario.on('click', '.btn-edit-item', function() {
        const itemId = $(this).data('id');
        // Adaptado para Laravel API
        $.get(`/api/inventario/${itemId}`, function(item) {
            form[0].reset();
            modalTitle.text('Editar Item del Inventario');
            itemIdInput.val(item.id);
            $('#nombre_item').val(item.nombre_item);
            $('#descripcion').val(item.descripcion);
            $('#departamento').val(item.departamento);
            $('#estado').val(item.estado);
            $('#stock').val(item.stock);
            $('#precio').val(item.precio);
            $('#fecha_adquisicion').val(item.fecha_adquisicion);
            toggleFormFields();
            modalInstance.show();
        }).fail(() => Swal.fire('Error', 'No se pudo obtener la información del item.', 'error'));
    });

    // ---- Lógica de CRUD ----
    form.submit(function(e) {
        e.preventDefault();
        const itemId = itemIdInput.val();
        const url = itemId ? `/api/inventario/${itemId}` : '/api/inventario';
        const method = itemId ? 'PUT' : 'POST';

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
    tablaInventario.on('click', '.btn-delete-item', function() {
        const itemId = $(this).data('id');
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
                    url: `/api/inventario/${itemId}`,
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
