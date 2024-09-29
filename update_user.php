<?php
header('Content-Type: application/json');
include 'includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$usuarioId = $data['usuario_id'];
$nombreUsuario = $data['nombre_usuario'];
$moduloId = $data['modulo_id'];
$tipoServicio = $data['tipo_servicio'];

// Consulta para actualizar el usuario
$sql = "UPDATE Usuarios SET nombre_usuario=?, modulo_id=?, tipo_servicio_id=? WHERE usuario_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nombreUsuario, $moduloId, $tipoServicio, $usuarioId);

$response = [];
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
