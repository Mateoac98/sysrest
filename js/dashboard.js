document.addEventListener('DOMContentLoaded', function() {
    const reiniciarTurnosLink = document.getElementById('reiniciarTurnos');
    const mensajeDiv = document.getElementById('mensaje');

    // Función para cargar estadísticas
    function loadStatistics() {
        fetch('get_statistics.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalAgendados').textContent = data.total_agendados;
                document.getElementById('totalAtendidos').textContent = data.total_atendidos;
                document.getElementById('totalFinalizados').textContent = data.total_finalizados;
                
                // Actualizar tipos de turnos
                const tiposTurnosList = document.getElementById('tiposTurnos');
                tiposTurnosList.innerHTML = '';
                data.tipos_turnos.forEach(turno => {
                    const li = document.createElement('li');
                    li.textContent = `${turno.nombre_turno}: ${turno.cantidad}`;
                    tiposTurnosList.appendChild(li);
                });

                // Actualizar servicios utilizados
                const serviciosUtilizadosList = document.getElementById('serviciosUtilizados');
                serviciosUtilizadosList.innerHTML = '';
                data.servicios_utilizados.forEach(servicio => {
                    const li = document.createElement('li');
                    li.textContent = `${servicio.nombre_servicio}: ${servicio.cantidad}`;
                    serviciosUtilizadosList.appendChild(li);
                });
            })
            .catch(error => console.error('Error al cargar estadísticas:', error));
    }

    if (reiniciarTurnosLink) {
        reiniciarTurnosLink.addEventListener('click', function(event) {
            event.preventDefault();

            if (confirm("¿Está seguro de que desea reiniciar todos los turnos?")) {
                fetch('reset_appointments.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mensajeDiv.textContent = "Los turnos se han reiniciado correctamente.";
                        loadStatistics(); // Recargar estadísticas después de reiniciar
                    } else {
                        mensajeDiv.textContent = "Error al reiniciar los turnos: " + data.error;
                    }
                })
                .catch(error => {
                    console.error('Error en la petición:', error);
                    mensajeDiv.textContent = "Error en la petición: " + error;
                });
            }
        });
    } else {
        console.error("El enlace de reiniciar turnos no fue encontrado.");
    }

    // Cargar estadísticas al iniciar
    loadStatistics();
    
    // Actualizar estadísticas cada 30 segundos (30000 ms)
    setInterval(loadStatistics, 30000);
});



