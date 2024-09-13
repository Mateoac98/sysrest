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

// Verifica si se han enviado datos mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $cliente_ID = $_POST['cliente_ID'];
    $tipo_servicio_ID = $_POST['tipo_servicio_ID'];
    $tipo_turno_ID = $_POST['tipo_turno_ID'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $estado = $_POST['estado'];

    // Preparar y ejecutar la consulta de inserción
    $stmt = $conn->prepare("INSERT INTO TurnosAgendados (cliente_ID, tipo_servicio_ID, tipo_turno_ID, fecha, hora, estado) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisss", $cliente_ID, $tipo_servicio_ID, $tipo_turno_ID, $fecha, $hora, $estado);

    if ($stmt->execute()) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>

