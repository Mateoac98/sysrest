<?php
// Conexión a la base de datos
include 'config.php';

// Verificar si la solicitud es una llamada AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consultar todos los clientes en la base de datos
    $sql = "SELECT cliente_ID, nombre, apellido, tipo_documento, numero_documento, nombre_completo FROM Clientes";
    $result = $conn->query($sql);

    $clientes = array();
    if ($result->num_rows > 0) {
        // Recorrer los resultados y almacenarlos en un array
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
    }

    // Retornar los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($clientes);
    exit;
}
?>

