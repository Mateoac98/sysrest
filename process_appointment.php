<?php

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
$data = $_POST;

// Verificar si los datos se recibieron correctamente
if (empty($data)) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Extraer y sanitizar los datos recibidos
$cliente_id = $conn->real_escape_string($data['cliente_id']);
$tipoServicio = $conn->real_escape_string($data['tipo_servicio_ID']);
$tipoTurno = $conn->real_escape_string($data['tipo_turno_ID']);
$fecha = $conn->real_escape_string($data['fecha']);
$hora = $conn->real_escape_string($data['hora']);

// Validar que todos los campos obligatorios se hayan enviado
if (empty($cliente_id) || empty($tipoServicio) || empty($tipoTurno) || empty($fecha) || empty($hora)) {
    echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
    exit;
}

// Asignación aleatoria de módulo
$moduloQuery = "SELECT modulo_id FROM modulos WHERE modulo_id NOT IN (
    SELECT modulo_id FROM turnosagendados WHERE fecha = '$fecha' GROUP BY modulo_id HAVING COUNT(*) >= 20
) ORDER BY RAND() LIMIT 1";

$moduloResult = $conn->query($moduloQuery);
if ($moduloResult->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'No hay módulos disponibles para esta fecha.']);
    exit;
}
$moduloRow = $moduloResult->fetch_assoc();
$modulo_ID = $moduloRow['modulo_id'];

// Insertar el turno en la base de datos
$sql = "INSERT INTO turnosagendados (cliente_id, tipo_servicio_id, tipo_turno_id, fecha, hora, estado, modulo_id) 
        VALUES ('$cliente_id', '$tipoServicio', '$tipoTurno', '$fecha', '$hora', 'Agendado', '$modulo_ID')";

if ($conn->query($sql) === TRUE) {
    $turno_id = $conn->insert_id; // Obtener el ID del turno recién creado

    // Obtener el nombre del cliente
    $clientQuery = "SELECT nombre_completo FROM clientes WHERE cliente_id = '$cliente_id'";
    $clientResult = $conn->query($clientQuery);
    $clientData = $clientResult->fetch_assoc();
    $nombre_cliente = $clientData['nombre_completo'];

    // Redirigir a add_appointment.php con los datos del ticket
    header("Location: add_appointment.php?turno_id=$turno_id&nombre_cliente=" . urlencode($nombre_cliente) . "&fecha=" . urlencode($fecha) . "&hora=" . urlencode($hora));
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
