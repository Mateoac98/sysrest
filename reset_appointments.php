<?php
session_start(); // Asegúrate de iniciar la sesión

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado.']);
    exit();
}

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

// Reiniciar el contador de turnos
$sql = "TRUNCATE TABLE turnosagendados"; // Elimina todos los registros
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al reiniciar los turnos: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
