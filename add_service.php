<?php
header('Content-Type: application/json');
include 'includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$nombreServicio = $data['nombre_servicio'];
$estado = $data['estado'];

$response = [];

// Consulta para insertar el nuevo servicio
$sql = "INSERT INTO tiposservicio (nombre_servicio, estado) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nombreServicio, $estado);

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>
