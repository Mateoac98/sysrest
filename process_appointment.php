<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'sysrest';
$username = 'root';
$password = 'nueva_contraseña';

$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Obtener datos de la solicitud
$data = $_POST;
var_dump($data); // Muestra los datos recibidos

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

// Asignación aleatoria de personal
$personalQuery = "SELECT personal_id FROM Personal WHERE personal_id NOT IN (
    SELECT personal_id FROM TurnosAgendados WHERE fecha = '$fecha' GROUP BY personal_id HAVING COUNT(*) >= 20
) ORDER BY RAND() LIMIT 1";

$personalResult = $conn->query($personalQuery);
if ($personalResult->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'No hay personal disponible para esta fecha.']);
    exit;
}
$personalRow = $personalResult->fetch_assoc();
$personal_ID = $personalRow['personal_id'];

// Insertar el turno en la base de datos
$sql = "INSERT INTO TurnosAgendados (cliente_id, tipo_servicio_id, tipo_turno_id, fecha, hora, estado, personal_id) 
        VALUES ('$cliente_id', '$tipoServicio', '$tipoTurno', '$fecha', '$hora', 'Agendado', '$personal_ID')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Turno registrado correctamente.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
