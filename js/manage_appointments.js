document.getElementById('callTurnoBtn').addEventListener('click', function() {
    fetch('call_turno.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('turnoId').innerText = 'Turno ID: ' + data.turno_id;
                document.getElementById('nombreCliente').innerText = 'Cliente: ' + data.nombre_cliente;
                document.getElementById('estadoCliente').innerText = 'Estado: ' + data.estado;
                document.getElementById('clienteInfo').style.display = 'block';
                showMessage('Turno llamado con éxito', 'success');
            } else {
                showMessage(data.error, 'danger');
            }
        })
        .catch(error => {
            showMessage('Error al llamar el turno', 'danger');
        });
});

document.getElementById('attendBtn').addEventListener('click', function() {
    const turnoId = document.getElementById('turnoId').innerText.split(': ')[1]; // Extrae el ID
    fetch('manage_appointments.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ turno_id: turnoId, accion: 'atender' }),
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            document.getElementById('estadoCliente').innerText = 'Estado: Atendido'; // Actualiza el estado en la interfaz
        }
    })
    .catch(error => {
        showMessage('Error al atender el turno', 'danger');
    });
});

document.getElementById('finalizeBtn').addEventListener('click', function() {
    const turnoId = document.getElementById('turnoId').innerText.split(': ')[1]; // Extrae el ID
    fetch('manage_appointments.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ turno_id: turnoId, accion: 'finalizar' }),
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            document.getElementById('estadoCliente').innerText = 'Estado: Finalizado'; // Actualiza el estado en la interfaz
        }
    })
    .catch(error => {
        showMessage('Error al finalizar el turno', 'danger');
    });
});

function showMessage(message, type) {
    const alertMessage = document.getElementById('alertMessage');
    alertMessage.innerText = message;
    alertMessage.className = type === 'danger' ? 'alert alert-danger' : 'alert alert-success';
    alertMessage.style.display = 'block';
}
