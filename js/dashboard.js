document.addEventListener("DOMContentLoaded", function() {
    const reiniciarTurnosButton = document.getElementById('reiniciarTurnos');
    const mensajeDiv = document.getElementById('mensaje');
    const ctx = document.getElementById('turnosChart').getContext('2d'); // Contexto del gráfico
    let turnosChart; // Variable para el gráfico

    // Verificar que el elemento de mensaje existe
    if (!mensajeDiv) {
        console.error("El contenedor de mensaje no fue encontrado.");
        return; // Salir si no se encuentra el contenedor
    }

    // Función para cargar las estadísticas
    function loadStatistics() {
        fetch('turno_statistics.php')
            .then(response => response.json())
            .then(data => {
                if (data.success === false) {
                    console.error(data.error);
                } else {
                    document.getElementById('pendientesCount').innerText = data.pendientes;
                    document.getElementById('atendidosCount').innerText = data.atendidos;
                    document.getElementById('finalizadosCount').innerText = data.finalizados;

                    // Actualizar gráfico
                    updateChart(data.pendientes, data.atendidos, data.finalizados);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para actualizar el gráfico
    function updateChart(pendientes, atendidos, finalizados) {
        if (turnosChart) {
            turnosChart.destroy(); // Destruir gráfico anterior
        }

        turnosChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pendientes', 'Atendidos', 'Finalizados'],
                datasets: [{
                    label: 'Cantidad de Turnos',
                    data: [pendientes, atendidos, finalizados],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Cargar estadísticas al iniciar
    loadStatistics();

    if (reiniciarTurnosButton) {
        reiniciarTurnosButton.addEventListener('click', function(event) {
            event.preventDefault(); // Evitar la acción predeterminada del enlace
            if (confirm("¿Está seguro de que desea reiniciar todos los turnos?")) {
                fetch('reset_appointments.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mensajeDiv.textContent = "Los turnos se han reiniciado correctamente.";
                        // Volver a cargar estadísticas después de reiniciar
                        loadStatistics();
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
        console.error("El botón de reiniciar turnos no fue encontrado.");
    }
});
