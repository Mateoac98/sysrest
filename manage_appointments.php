<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username']) || !isset($_SESSION['modulo_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$modulo_id = $_SESSION['modulo_id']; // Módulo del usuario autenticado

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', 'nueva_contraseña', 'sysrest');

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Procesar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $accion = $data['accion'];
    $turno_id = $data['turno_id'];

    if ($accion === 'atender' || $accion === 'finalizar') {
        $nuevo_estado = ($accion === 'atender') ? 'Atendido' : 'Finalizado';

        $query = "UPDATE turnosagendados SET estado = ? WHERE turno_id = ? AND modulo_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $nuevo_estado, $turno_id, $modulo_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "Turno $nuevo_estado con éxito."]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al actualizar el turno: ' . $stmt->error]);
        }
        $stmt->close();
        exit; // Salir después de procesar el POST
    }
}

// Consultar turnos pendientes solo para el módulo del usuario
$query = "SELECT t.turno_id, c.nombre_completo 
          FROM turnosagendados t 
          JOIN clientes c ON t.cliente_id = c.cliente_id 
          WHERE t.estado = 'Pendiente' AND t.modulo_id = ? LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $modulo_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Turnos</title>
    <link rel="stylesheet" href="css/manage_appointments.css">
</head>
<body>

<div class="container">
    <h1>Bienvenido <?php echo htmlspecialchars($username); ?></h1>
    <h2>Gestión de Turnos</h2>

    <button id="callTurnoBtn">Llamar Turno</button>

    <div id="clienteInfo" class="cliente-info" style="display: none;">
        <h3>Información del Cliente</h3>
        <p id="turnoId"></p>
        <p id="nombreCliente"></p>
        <p id="estadoCliente"></p>
        <button id="attendBtn">Atender</button>
        <button id="finalizeBtn">Finalizar</button>
    </div>

    <div id="alertMessage" class="alert" style="display: none;"></div>
</div>

<script src="js/manage_appointments.js"></script>

<?php
// Mostrar información del turno si existe
if ($result->num_rows > 0) {
    $turno = $result->fetch_assoc();
    $turno_id = $turno['turno_id'];
    $nombre_cliente = $turno['nombre_completo'];

    echo "<script>
            document.getElementById('clienteInfo').style.display = 'block';
            document.getElementById('turnoId').innerText = 'Turno ID: $turno_id';
            document.getElementById('nombreCliente').innerText = 'Cliente: $nombre_cliente';
            document.getElementById('estadoCliente').innerText = 'Estado: Pendiente'; // Estado inicial
            alert('Llamado al cliente: ' + '$nombre_cliente' + ' con Turno ID: ' + '$turno_id');

          </script>";
} else {
    echo "<script>
            document.getElementById('alertMessage').innerText = 'No hay turnos pendientes para su módulo.';
            document.getElementById('alertMessage').style.display = 'block';
          </script>";
}

// Cerrar la conexión
$conn->close();
?>

</body>
</html>
