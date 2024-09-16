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

$nombre = $conn->real_escape_string($data['nombre']);
$documento = $conn->real_escape_string($data['documento']);
$numeroDocumento = $conn->real_escape_string($data['numeroDocumento']);
$tipoServicio = $conn->real_escape_string($data['tipoServicio']);
$tipoTurno = $conn->real_escape_string($data['tipoTurno']);

// Insertar datos en la base de datos
$sql = "INSERT INTO TurnosAgendados (cliente_ID, tipo_servicio_ID, tipo_turno_ID, fecha, hora, estado) 
        VALUES ((SELECT cliente_ID FROM Clientes WHERE numero_documento='$numeroDocumento'), 
                (SELECT tipo_servicio_ID FROM TiposServicio WHERE nombre_servicio='$tipoServicio'), 
                (SELECT tipo_turno_ID FROM TiposTurno WHERE nombre_turno='$tipoTurno'), 
                CURDATE(), 
                CURTIME(), 
                'Agendado')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Turno registrado correctamente.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
}

$conn->close();
?>

