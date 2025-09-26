// js/suscripciones.js (Adaptado para Laravel)
$(document).ready(function() {
    // ---- Lógica para el CRUD de Planes ----
    const modalPlanEl = document.getElementById('modal-plan');
    const modalPlanInstance = new bootstrap.Modal(modalPlanEl);
    const formPlan = $('#form-plan');

    // Usaremos DataTable para una mejor experiencia
    let planesTable;

    function inicializarTablaPlanes() {
        const container = $('#lista-planes-container');
        container.html(`
            <table id="tabla-planes" class="table table-hover" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Nombre del Plan</th>
                        <th>Precio Base</th>
                        <th>Duración</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `);

        planesTable = $('#tabla-planes').DataTable({
            processing: true,
            serverSide: true,
            // Adaptado para Laravel API
            ajax: {
                url: "/api/planes",
                type: "GET"
            },
            columns: [
                { data: 'nombre_plan' },
                { data: 'precio_base', render: $.fn.dataTable.render.number(',', '.', 2, '$') },
                { data: 'duracion_dias', render: function(data) { return `${data} días`; } },
                { 
                    data: 'estatus',
                    render: function(data) {
                        const badgeClass = data === 'activo' ? 'bg-success' : 'bg-secondary';
                        return `<span class="badge ${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (USER_ROLE === 'admin' || USER_ROLE === 'supervisor') {
                            return `
                                <button class="btn btn-sm btn-info btn-edit-plan" data-id="${data}"><i class="fa-solid fa-pencil"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-plan" data-id="${data}"><i class="fa-solid fa-trash"></i></button>
                            `;
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
    }

    // Carga inicial de la tabla
    inicializarTablaPlanes();

    // Abrir modal para NUEVO plan
    $('#btn-nuevo-plan').click(function() {
        formPlan[0].reset();
        $('#plan_id').val('');
        $('#modal-plan-titulo').text('Crear Nuevo Plan');
        modalPlanInstance.show();
    });

    // Abrir modal para EDITAR plan
    $('#lista-planes-container').on('click', '.btn-edit-plan', function() {
        const planId = $(this).data('id');
        // Adaptado para Laravel API
        $.get(`/api/planes/${planId}`, function(plan) {
            $('#plan_id').val(plan.id);
            $('#nombre_plan').val(plan.nombre_plan);
            $('#descripcion_plan').val(plan.descripcion);
            $('#precio_base_plan').val(plan.precio_base);
            $('#duracion_dias_plan').val(plan.duracion_dias);
            $('#estatus_plan').val(plan.estatus);
            $('#modal-plan-titulo').text('Editar Plan');
            modalPlanInstance.show();
        }).fail(() => Swal.fire('Error', 'No se pudo obtener la información del plan.', 'error'));
    });

    // Enviar formulario (Crear/Actualizar)
    formPlan.submit(function(e) {
        e.preventDefault();
        const planId = $('#plan_id').val();
        const url = planId ? `/api/planes/${planId}` : '/api/planes';
        const method = planId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {
                modalPlanInstance.hide();
                Swal.fire('¡Éxito!', response.message, 'success');
                planesTable.ajax.reload();
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

    // ELIMINAR plan
    $('#lista-planes-container').on('click', '.btn-delete-plan', function() {
        const planId = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sí, ¡eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Adaptado para Laravel API
                $.ajax({
                    url: `/api/planes/${planId}`,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire('¡Eliminado!', response.message, 'success');
                        planesTable.ajax.reload();
                    },
                    error: (jqXHR) => Swal.fire('Error', jqXHR.responseJSON?.message || 'No se pudo eliminar.', 'error')
                });
            }
        });
    });
});
