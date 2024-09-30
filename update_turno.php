<?php
session_start();
$host = 'localhost';
$dbname = 'sysrest';
$usernameDB = 'root';
$passwordDB = 'nueva_contraseña';

$conn = new mysqli($host, $usernameDB, $passwordDB, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Conexión fallida: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$estado = $data['estado'];
$turno_id = $data['turno_id']; // Asegúrate de que el ID del turno se envíe en el cuerpo de la solicitud

// Usar una sentencia preparada para evitar inyección SQL
$query = "UPDATE turnosagendados SET estado = ? WHERE turno_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $estado, $turno_id); // 's' para string, 'i' para integer

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar el estado: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
