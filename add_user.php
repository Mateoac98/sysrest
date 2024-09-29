<?php
header('Content-Type: application/json');
include 'includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$nombreUsuario = $data['nombre_usuario'];
$moduloId = $data['modulo_id'];
$tipoServicioId = $data['tipo_servicio'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); // Hasheando la contraseña

$response = [];

// Consulta para insertar el nuevo usuario
$sql = "INSERT INTO usuarios (nombre_usuario, contraseña, modulo_id, tipo_servicio_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $nombreUsuario, $password, $moduloId, $tipoServicioId);

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
