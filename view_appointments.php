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
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar turnos para el módulo del usuario
$query = "SELECT t.turno_id, t.estado, t.modulo_id, c.nombre_completo 
          FROM turnosagendados t 
          JOIN clientes c ON t.cliente_id = c.cliente_id 
          WHERE t.modulo_id = ?";

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
    <title>Ver Turnos</title>
    <link rel="stylesheet" href="css/view_appointments.css">
</head>
<body>

<div class="container">
    <h1>SYSREST</h1>
    <h2>Lista de Turnos</h2>

    <table id="appointmentsTable">
        <thead>
            <tr>
                <th>Turno ID</th>
                <th>Estado</th>
                <th>Módulo ID</th>
                <th>Cliente</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($turno = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $turno['turno_id']; ?></td>
                    <td><?php echo $turno['estado']; ?></td>
                    <td><?php echo $turno['modulo_id']; ?></td>
                    <td><?php echo $turno['nombre_completo']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div id="alertMessage" style="display:none; color: red;"></div>
</div>

<script src="js/view_appointments.js"></script>

<?php
// Cerrar la conexión
$conn->close();
?>

</body>
</html>
