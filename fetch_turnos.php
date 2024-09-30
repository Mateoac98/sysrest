<?php
session_start();
if (!isset($_SESSION['modulo_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado.']);
    exit;
}

$modulo_id = $_SESSION['modulo_id'];
$conn = new mysqli('localhost', 'root', 'nueva_contraseña', 'sysrest');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Modificar la consulta para incluir el nombre del cliente
$query = "
    SELECT ta.turno_id, ta.estado, ta.modulo_id, c.nombre_completo 
    FROM turnosagendados ta 
    JOIN clientes c ON ta.cliente_id = c.cliente_id 
    WHERE ta.modulo_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $modulo_id);
$stmt->execute();
$result = $stmt->get_result();

$turnos = [];
while ($row = $result->fetch_assoc()) {
    $turnos[] = $row;
}

echo json_encode(['success' => true, 'turnos' => $turnos]);
$conn->close();
?>
