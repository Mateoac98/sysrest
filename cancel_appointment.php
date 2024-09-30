<?php
session_start(); // Asegúrate de iniciar la sesión

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'sysrest';
$usernameDB = 'root'; // Tu usuario de base de datos
$passwordDB = 'nueva_contraseña'; // Tu contraseña de base de datos

// Conexión a la base de datos
$conn = new mysqli($host, $usernameDB, $passwordDB, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado.']);
    exit;
}

// Obtener datos de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$turnoId = $conn->real_escape_string($data['turno_id']);

// Validar que el ID del turno no esté vacío
if (empty($turnoId)) {
    echo json_encode(['success' => false, 'error' => 'ID del turno inválido.']);
    exit;
}

// Cambiar el estado del turno a "Cancelado"
$sql = "UPDATE turnosagendados SET estado = 'Cancelado' WHERE turno_id = '$turnoId'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
