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

// Actualizar la fecha y la hora
function updateDateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
    const year = now.getFullYear();
    
    const date = `${day}/${month}/${year}`; // Formato DD/MM/YYYY

    document.getElementById("currentTime").textContent = `${hours}:${minutes}:${seconds}`;
    document.getElementById("currentDate").textContent = date;

    // Llenar los campos ocultos
    document.getElementById("fecha").value = `${year}-${month}-${day}`; // Formato YYYY-MM-DD para el envío
    document.getElementById("hora").value = `${hours}:${minutes}:${seconds}`; // Formato HH:MM:SS
}

// Actualizar la fecha y hora cada segundo
setInterval(updateDateTime, 1000);
updateDateTime();

var numeroDeTurnoActual = 0;

// Función para solicitar el turno
function solicitarTurno(tipoTurno) {
    const nombre = document.getElementById("nombre").value;
    const documento = document.getElementById("documento").value;
    const numeroDocumento = document.getElementById("numeroDocumento").value;
    const tipoServicio = document.getElementById("tipoServicio").value;

    // Validación de campos
    if (!nombre || documento === "na" || !numeroDocumento || tipoServicio === "na") {
        showWarning("Por favor, llene todos los campos requeridos antes de continuar.");
        return;
    } else {
        hideWarning();
    }

    // Incrementar el número de turno
    numeroDeTurnoActual++;
    if (numeroDeTurnoActual <= 100) {
        // Registrar el turno usando AJAX
        const turnoData = {
            nombre: nombre,
            documento: documento,
            numeroDocumento: numeroDocumento,
            tipoServicio: tipoServicio,
            tipoTurno: tipoTurno,
            fecha: document.getElementById("fecha").value, // Fecha actual en formato YYYY-MM-DD
            hora: document.getElementById("hora").value    // Hora actual
        };

        // Hacer la llamada AJAX para registrar el turno
        fetch('process_appointment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(turnoData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Turno registrado con éxito.');
                document.getElementById('page2').style.display = 'none';
                document.getElementById('page3').style.display = 'block';
                document.querySelector('.numero-turno').textContent = pad(numeroDeTurnoActual, 3);
            } else {
                console.error('Error al registrar el turno:', data.error);
            }
        })
        .catch(error => {
            console.error('Error en la petición AJAX:', error);
        });

    } else {
        alert('Lo sentimos, se han agotado los turnos disponibles.');
        numeroDeTurnoActual = 0;
    }
}

// Función para restablecer los campos del formulario
function resetFields() {
    document.getElementById("nombre").value = "";
    document.getElementById("documento").value = "na";
    document.getElementById("numeroDocumento").value = "";
    document.getElementById("tipoServicio").value = "na";
}

// Función para mostrar un mensaje de advertencia
function showWarning(message) {
    const warningMessage = document.getElementById('warningMessage');
    warningMessage.textContent = message;
    warningMessage.style.display = 'block';
}

// Función para ocultar un mensaje de advertencia
function hideWarning() {
    const warningMessage = document.getElementById('warningMessage');
    warningMessage.textContent = '';
    warningMessage.style.display = 'none';
}

// Cargar los clientes mediante AJAX
function loadClients() {
    fetch('http://localhost/turnos/list_clients.php')
    .then(response => response.json())
    .then(data => {
        const clientsList = document.getElementById('clientsList');
        clientsList.innerHTML = '';

        data.forEach(client => {
            const li = document.createElement('li');
            li.textContent = `${client.nombre_completo} (${client.numero_documento})`;
            clientsList.appendChild(li);
        });
    })
    .catch(error => {
        console.error('Error al cargar los clientes:', error);
    });
}

// Cargar los clientes al iniciar
document.addEventListener('DOMContentLoaded', loadClients);
