function fetchTurnos() {
    fetch('fetch_turnos.php')
        .then(response => response.json())
        .then(data => {
            console.log(data); // Agrega esta línea para ver la respuesta
            const tbody = document.querySelector('#appointmentsTable tbody');
            tbody.innerHTML = ''; // Limpiar el contenido previo

            if (data.success) {
                if (data.turnos.length > 0) {
                    data.turnos.forEach(turno => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${turno.turno_id}</td>
                            <td>${turno.estado}</td>
                            <td>${turno.modulo_id}</td>
                            <td>${turno.nombre_completo}</td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="3">No hay turnos disponibles.</td>';
                    tbody.appendChild(row);
                }
            } else {
                const alertMessage = document.getElementById('alertMessage');
                alertMessage.innerText = data.error;
                alertMessage.style.display = 'block';
            }
        })
        .catch(error => {
            const alertMessage = document.getElementById('alertMessage');
            alertMessage.innerText = 'Error al obtener turnos';
            alertMessage.style.display = 'block';
            console.error(error); // Agrega esta línea para más detalles en la consola
        });
}

// Llamar a la función de actualización cada 5 segundos
setInterval(fetchTurnos, 5000);
fetchTurnos(); // Llamar inicialmente
