<?php
header('Content-Type: application/json');
include 'includes/config.php';

if (isset($_GET['id'])) {
    $serviceId = intval($_GET['id']);
    
    $sql = "DELETE FROM tiposservicio WHERE tipo_servicio_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $serviceId);
    
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
} else {
    echo json_encode(['success' => false, 'error' => 'ID no especificado']);
}
?>
