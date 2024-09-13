<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'sysrest';
$username = 'root';
$password = 'nueva_contraseña';

// Crear la conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se han enviado datos mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $cliente_ID = $_POST['cliente_ID'];
    $tipo_servicio_ID = $_POST['tipo_servicio_ID'];
    $tipo_turno_ID = $_POST['tipo_turno_ID'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $estado = 'Agendado'; // Estado por defecto

    // Validar datos
    if (empty($cliente_ID) || empty($tipo_servicio_ID) || empty($tipo_turno_ID) || empty($fecha) || empty($hora)) {
        die("Todos los campos son requeridos.");
    }

    // Preparar y ejecutar la consulta de inserción
    $stmt = $conn->prepare("INSERT INTO TurnosAgendados (cliente_ID, tipo_servicio_ID, tipo_turno_ID, fecha, hora, estado) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param('ssssss',$cliente_ID, $tipo_servicio_ID, $tipo_turno_ID, $fecha, $hora, $estado);

    if ($stmt->execute()) {

        $data = new stdClass();
        header('Content-Type: application/json; charset=utf-8');
        $data->message = "Nuevo registro creado exitosamente";
        echo json_encode($data);
    } else {
        echo "Error al ejecutar la consulta: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "no es post";
}
?>