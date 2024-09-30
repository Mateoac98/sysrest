<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/config.php';

// Obtener datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['usuario_id']) && isset($data['tipo_servicio'])) {
    $usuario_id = $data['usuario_id'];
    $tipo_servicio = $data['tipo_servicio'];

    // Aquí va la consulta para actualizar el usuario
    $sql = "UPDATE usuarios SET tipo_servicio_id = ? WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $tipo_servicio, $usuario_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}
?>
