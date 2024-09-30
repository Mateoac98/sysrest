<?php
session_start();
if (!isset($_SESSION['modulo_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado.']);
    exit;
}

$conn = new mysqli('localhost', 'root', 'nueva_contraseña', 'sysrest');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Consultar estadísticas
$query = "SELECT 
            (SELECT COUNT(*) FROM turnosagendados WHERE estado = 'Pendiente') AS pendientes,
            (SELECT COUNT(*) FROM turnosagendados WHERE estado = 'Atendido') AS atendidos,
            (SELECT COUNT(*) FROM turnosagendados WHERE estado = 'Finalizado') AS finalizados";

$result = $conn->query($query);
$data = $result->fetch_assoc();

$conn->close();

echo json_encode($data);
?>
