<?php
// Conexión a la base de datos
include 'config.php';

// Verificar si la solicitud es una llamada AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consultar todas las citas agendadas en la base de datos
    $sql = "SELECT turno_ID, cliente_ID, tipo_servicio_ID, tipo_turno_ID, fecha, hora, estado FROM TurnosAgendados";
    $result = $conn->query($sql);

    $turnos = array();
    if ($result->num_rows > 0) {
        // Recorrer los resultados y almacenarlos en un array
        while ($row = $result->fetch_assoc()) {
            $turnos[] = $row;
        }
    }

    // Retornar los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($turnos);
    exit;
}
?>

