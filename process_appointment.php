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

// Obtener datos JSON de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos se recibieron correctamente
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Extraer y sanitizar los datos recibidos
$nombre = $conn->real_escape_string($data['nombre']);
$documento = $conn->real_escape_string($data['documento']);
$numeroDocumento = $conn->real_escape_string($data['numeroDocumento']);
$tipoServicio = $conn->real_escape_string($data['tipoServicio']);
$tipoTurno = $conn->real_escape_string($data['tipoTurno']);
$personal_ID = $conn->real_escape_string($data['personal_ID']);

// Validar que todos los campos obligatorios se hayan enviado
if (empty($nombre) || empty($documento) || empty($numeroDocumento) || empty($tipoServicio) || empty($tipoTurno) || empty($personal_ID)) {
    echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
    exit;
}

// Verificar si el cliente, servicio, turno y personal existen
$clienteQuery = "SELECT cliente_ID FROM Clientes WHERE numero_documento='$numeroDocumento'";
$clienteResult = $conn->query($clienteQuery);
if ($clienteResult->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Cliente no encontrado']);
    exit;
}
$clienteRow = $clienteResult->fetch_assoc();
$cliente_ID = $clienteRow['cliente_ID'];

$servicioQuery = "SELECT tipo_servicio_ID FROM TiposServicio WHERE nombre_servicio='$tipoServicio'";
$servicioResult = $conn->query($servicioQuery);
if ($servicioResult->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Tipo de servicio no encontrado']);
    exit;
}
$servicioRow = $servicioResult->fetch_assoc();
$tipo_servicio_ID = $servicioRow['tipo_servicio_ID'];

$turnoQuery = "SELECT tipo_turno_ID FROM TiposTurno WHERE nombre_turno='$tipoTurno'";
$turnoResult = $conn->query($turnoQuery);
if ($turnoResult->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Tipo de turno no encontrado']);
    exit;
}
$turnoRow = $turnoResult->fetch_assoc();
$tipo_turno_ID = $turnoRow['tipo_turno_ID'];

// Insertar el turno en la base de datos, asignando personal
$sql = "INSERT INTO TurnosAgendados (cliente_ID, tipo_servicio_ID, tipo_turno_ID, fecha, hora, estado, personal_id) 
        VALUES ('$cliente_ID', '$tipo_servicio_ID', '$tipo_turno_ID', CURDATE(), CURTIME(), 'Agendado', '$personal_ID')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Turno registrado correctamente.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>

