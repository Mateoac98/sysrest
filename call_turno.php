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

// Selecciona un turno agendado
$query = "SELECT t.turno_id, c.nombre_completo FROM turnosagendados t JOIN clientes c ON t.cliente_id = c.cliente_id WHERE t.estado = 'Agendado' LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $turno = $result->fetch_assoc();
    $turno_id = $turno['turno_id'];

    // Cambia el estado del turno a 'En Proceso'
    $updateQuery = "UPDATE turnosagendados SET estado = 'En Proceso' WHERE turno_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $turno_id);
    $updateStmt->execute();

    echo json_encode([
        'success' => true,
        'turno_id' => $turno['turno_id'],
        'nombre_cliente' => $turno['nombre_completo'],
        'estado' => 'En Proceso'
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'No hay turnos disponibles.']);
}

$conn->close();
?>
