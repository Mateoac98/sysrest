<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Obtener datos JSON
$data = json_decode(file_get_contents('php://input'), true);

$modulo_id = $data['modulo_id'];

// Eliminar módulo
$sql = "DELETE FROM modulos WHERE modulo_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $modulo_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
