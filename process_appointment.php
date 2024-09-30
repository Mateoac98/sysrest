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
$data = $_POST;

// Verificar si los datos se recibieron correctamente
if (empty($data)) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Extraer y sanitizar los datos recibidos
$tipoDocumento = $conn->real_escape_string($data['tipo_documento']);
$numeroDocumento = $conn->real_escape_string($data['numero_documento']);
$tipoServicio = $conn->real_escape_string($data['tipo_servicio_ID']);
$tipoTurno = $conn->real_escape_string($data['tipo_turno_ID']);
$fecha = $conn->real_escape_string($data['fecha']);
$hora = $conn->real_escape_string($data['hora']);

// Validar que todos los campos obligatorios se hayan enviado
if (empty($tipoDocumento) || empty($numeroDocumento) || empty($tipoServicio) || empty($tipoTurno) || empty($fecha) || empty($hora)) {
    echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
    exit;
}

// Obtener el cliente_id a partir del tipo_documento y numero_documento
$clientQuery = "SELECT cliente_id, nombre_completo FROM clientes WHERE tipo_documento = '$tipoDocumento' AND numero_documento = '$numeroDocumento'";
$clientResult = $conn->query($clientQuery);

if ($clientResult->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Cliente no encontrado.']);
    exit;
}

$clientData = $clientResult->fetch_assoc();
$cliente_id = $clientData['cliente_id'];
$nombre_cliente = $clientData['nombre_completo'];

// Obtener el modulo_id según el tipo de servicio
$modulo_ID = obtenerModuloPorTipoServicio($tipoServicio, $conn);
if ($modulo_ID === null) {
    echo json_encode(['success' => false, 'error' => 'Módulo no disponible para este tipo de servicio.']);
    exit;
}

// Insertar el turno en la base de datos
$sql = "INSERT INTO turnosagendados (cliente_id, tipo_servicio_id, tipo_turno_id, fecha, hora, estado, modulo_id) 
        VALUES ('$cliente_id', '$tipoServicio', '$tipoTurno', '$fecha', '$hora', 'Agendado', '$modulo_ID')";

if ($conn->query($sql) === TRUE) {
    $turno_id = $conn->insert_id; // Obtener el ID del turno recién creado

    // Redirigir a add_appointment.php con los datos del ticket
    header("Location: add_appointment.php?turno_id=$turno_id&nombre_cliente=" . urlencode($nombre_cliente) . "&fecha=" . urlencode($fecha) . "&hora=" . urlencode($hora));
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Error: ' . $conn->error]);
}

// Función para obtener el módulo por tipo de servicio
function obtenerModuloPorTipoServicio($tipo_servicio_id, $conn) {
    // Priorizar los turnos preferenciales
    $turnoPreferencial = 2; // ID del tipo de turno preferencial
    $moduloQuery = "SELECT modulo_id FROM usuarios WHERE tipo_servicio_id = '$tipo_servicio_id'";

    // Verificar si hay turnos preferenciales disponibles
    $preferentialQuery = "SELECT modulo_id FROM turnosagendados WHERE tipo_turno_id = '$turnoPreferencial' AND fecha = CURDATE() GROUP BY modulo_id";
    $preferentialResult = $conn->query($preferentialQuery);

    if ($preferentialResult->num_rows > 0) {
        // Si hay turnos preferenciales, obtener el módulo de los usuarios
        $result = $conn->query($moduloQuery);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['modulo_id'];
        }
    } else {
        // Si no hay turnos preferenciales, usar el módulo correspondiente
        $result = $conn->query($moduloQuery);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['modulo_id'];
        }
    }

    return null; // Si no se encuentra, retorna null
}

// Cerrar la conexión
$conn->close();
?>
