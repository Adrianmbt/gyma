// js/app.js (Adaptado para Laravel)

/**
 * Carga y actualiza los datos de las tarjetas del dashboard del administrador.
 */
function cargarDatosDashboardAdmin() {
    // Se asegura de que la variable USER_ROLE exista, sea 'admin', y que el panel de análisis esté visible en el DOM.
    if (typeof USER_ROLE !== 'undefined' && USER_ROLE === 'admin' && $('#analisis').length) {
        
        console.log('Solicitando actualización de datos del dashboard a Laravel...');

        // Usamos la nueva ruta de la API de Laravel
        $.get('/api/reportes/dashboard-stats', function(data) {
            // La respuesta de Laravel ya viene en el formato correcto
            const formatCurrency = (value) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value || 0);
            
            $('#total-suscripciones-dia').text(formatCurrency(data.total_suscripciones_dia));
            $('#total-ventas-dia').text(formatCurrency(data.total_ventas_dia));
            $('#gran-total-dia').text(formatCurrency(data.gran_total_dia));
            console.log('Dashboard de Laravel actualizado:', data);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Fallo en la conexión con la API de reportes de Laravel.', textStatus, errorThrown);
        });
    }
}

// Ejecuta el siguiente código una vez que toda la página (DOM) se haya cargado.
$(document).ready(function() {
    
    window.tasaBCV = 0;
    function actualizarTasaBCV() {
        const elementoValor = document.getElementById('valor-tasa-bcv');
        const elementoFecha = document.getElementById('fecha-tasa-bcv');
        if (!elementoValor || !elementoFecha) return;

        // Usamos la nueva ruta de la API de Laravel
        fetch('/api/bcv')
            .then(response => response.json())
            .then(data => {
                // La respuesta de Laravel ya viene en el formato correcto
                if (data.precio > 0) {
                    window.tasaBCV = data.precio;
                    elementoValor.textContent = new Intl.NumberFormat('es-VE', { style: 'currency', currency: 'VES' }).format(window.tasaBCV);
                    elementoFecha.textContent = 'Valor al ' + new Date().toLocaleDateString('es-VE');
                } else {
                    throw new Error(data.message || 'Respuesta no exitosa de la API del BCV de Laravel');
                }
            })
            .catch(error => {
                elementoValor.textContent = 'Error';
                elementoValor.classList.add('text-danger');
                elementoFecha.textContent = 'No se pudo obtener la tasa.';
                console.error('Error BCV (Laravel):', error);
            });
    }
    actualizarTasaBCV();

    // Llama a la función del dashboard por primera vez al cargar la página.
    cargarDatosDashboardAdmin();

    // Recarga los datos del dashboard cada vez que el admin hace clic en la pestaña de "Análisis y Reportes".
    $('#analisis-tab').on('shown.bs.tab', function () {
        cargarDatosDashboardAdmin();
    });
});
