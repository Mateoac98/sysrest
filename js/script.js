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

// Función para restablecer los campos del formulario
function resetFields() {
    document.getElementById("nombre").value = "";
    document.getElementById("documento").value = "na";
    document.getElementById("numeroDocumento").value = "";
    document.getElementById("tipoServicio").value = "na";
}

// Función para actualizar la fecha y la hora
function updateDateTime() {
    const currentDateTime = new Date();
    const currentTime = currentDateTime.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    const currentDate = currentDateTime.toLocaleDateString('es-ES');

    document.getElementById("currentTime").textContent = currentTime;
    document.getElementById("currentDate").textContent = currentDate;
    document.getElementById("currentTimeForm").textContent = currentTime;
    document.getElementById("currentDateForm").textContent = currentDate;
    document.getElementById("currentDatePage3").textContent = currentDate;
    document.getElementById("currentTimePage3").textContent = currentTime;
}

// Actualizar la fecha y la hora cada segundo
setInterval(updateDateTime, 1000);
updateDateTime();

var numeroDeTurnoActual = 0;

// Función para ir a la página 2
function goToPage2() {
    resetFields();
    document.getElementById("page1").style.display = "none";
    document.getElementById("page2").style.display = "block";
}

const ingresarBtn = document.getElementById("ingresarBtn");
ingresarBtn.addEventListener("click", goToPage2);

// Función para redirigir a la página 3
function redirectToPage3(tipoTurno) {
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
        document.getElementById('page2').style.display = 'none';
        document.getElementById('page3').style.display = 'block';
        document.querySelector('.numero-turno').textContent = pad(numeroDeTurnoActual, 3);

        // Registrar el turno usando AJAX
        const turnoData = {
            nombre: nombre,
            documento: documento,
            numeroDocumento: numeroDocumento,
            tipoServicio: tipoServicio,
            tipoTurno: tipoTurno
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

// Manejar el clic en los botones de tipo de turno
document.getElementById('generalBtn').addEventListener('click', function() {
    redirectToPage3('General');
});

document.getElementById('preferencialBtn').addEventListener('click', function() {
    redirectToPage3('Preferencial');
});

// Manejar el clic en los botones sí/no
document.getElementById('siBtn').addEventListener('click', function() {
    redirectToPage2();
});

document.getElementById('noBtn').addEventListener('click', function() {
    alert('Gracias por usar SYSREST. El proceso ha finalizado.');
    document.getElementById('page3').style.display = 'none';
    document.getElementById('page1').style.display = 'block';
});

// Función para redirigir a la página 2 desde la página 3
function redirectToPage2() {
    resetFields();
    document.getElementById('page3').style.display = 'none';
    document.getElementById('page2').style.display = 'block';
}

// Función para añadir ceros a la izquierda en los números
function pad(number, length) {
    return (number + '').padStart(length, '0');
}

// Función para cargar los clientes mediante AJAX
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

// Función para cargar los turnos agendados mediante AJAX
function loadAppointments() {
    fetch('view_appointments.php')
    .then(response => response.json())
    .then(data => {
        const appointmentsList = document.getElementById('appointmentsList');
        appointmentsList.innerHTML = '';

        data.forEach(appointment => {
            const li = document.createElement('li');
            li.textContent = `Turno ${appointment.turno_ID}: ${appointment.nombre} - ${appointment.tipo_servicio} (${appointment.fecha} a las ${appointment.hora})`;
            appointmentsList.appendChild(li);
        });
    })
    .catch(error => {
        console.error('Error al cargar los turnos agendados:', error);
    });
}

// Cargar los clientes al iniciar
document.addEventListener('DOMContentLoaded', loadClients);
