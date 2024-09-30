<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Obtener datos JSON
$data = json_decode(file_get_contents('php://input'), true);

$nombre_modulo = $data['nombre_modulo'];
$estado = $data['estado'];

// Insertar nuevo módulo
$sql = "INSERT INTO modulos (nombre, estado) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nombre_modulo, $estado);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
