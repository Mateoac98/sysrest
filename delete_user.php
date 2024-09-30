<?php
header('Content-Type: application/json');
include 'includes/config.php';

if (isset($_GET['id'])) {
    $usuarioId = intval($_GET['id']);
    
    $sql = "DELETE FROM usuarios WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuarioId);
    
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
