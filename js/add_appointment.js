// Mostrar aviso para agendar otro turno
function showAppointmentAlert() {
    if (confirm("¿Desea agendar otro turno?")) {
        window.location.href = "add_appointment.php"; // Redirigir a la misma página
    } else {
        window.close(); // Cerrar la pestaña o ventana
    }
}

// Llamar a la función cuando se cargue el documento
document.addEventListener("DOMContentLoaded", function() {
    // Verificar si hay información de ticket
    const turnoId = new URLSearchParams(window.location.search).get('turno_id');
    const nombreCliente = new URLSearchParams(window.location.search).get('nombre_cliente');

    if (turnoId && nombreCliente) {
        showAppointmentAlert();
    }
});

// Actualizar la hora automáticamente
window.onload = function() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('hora').value = `${hours}:${minutes}`;
}

// Actualizar la hora cada minuto
setInterval(updateTime, 60000);
// También llamar a la función inmediatamente al cargar
updateTime();
