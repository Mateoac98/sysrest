<?php
header('Content-Type: application/json');
include 'includes/config.php';

// Obtener datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['service_id']) && isset($data['estado'])) {
    $service_id = $data['service_id'];
    $estado = $data['estado'];

    // Consulta para actualizar el estado del servicio
    $sql = "UPDATE tiposservicio SET estado = ? WHERE tipo_servicio_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $estado, $service_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}
?>
