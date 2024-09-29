<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Agregar nuevo cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Establece el estado por defecto
    $estado = "Activo"; 
    
    // Prepara la consulta para insertar un nuevo cliente
    $stmt = $conn->prepare("INSERT INTO Clientes (nombre_completo, tipo_documento, numero_documento, estado) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $data['nombre_completo'], $data['tipo_documento'], $data['numero_documento'], $estado);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;
}

// Si no es un POST, se puede manejar como un error
http_response_code(405); // Método no permitido
echo json_encode(['success' => false, 'error' => 'Método no permitido']);
exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    var_dump($data); // Verifica qué datos se están recibiendo
    exit; // Termina aquí para ver los datos
}
