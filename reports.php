<?php
session_start();

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'sysrest';
$usernameDB = 'root';
$passwordDB = 'nueva_contraseña';

$conn = new mysqli($host, $usernameDB, $passwordDB, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializa contadores
$pendientes = 0;
$en_proceso = 0;
$finalizados = 0;

// Realiza la consulta
$sql = "SELECT estado, COUNT(*) as count FROM turnosagendados GROUP BY estado";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        switch ($row['estado']) {
            case 'agendado':
                $pendientes = $row['count'];
                break;
            case 'en proceso':
                $en_proceso = $row['count'];
                break;
            case 'finalizado':
                $finalizados = $row['count'];
                break;
        }
    }
} else {
    echo "Error en la consulta: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="reportes-container">
        <h1>Estadísticas de Turnos</h1>
        <div class="estadisticas">
            <div>Pendientes: <span id="pendientesCount"><?php echo $pendientes; ?></span></div>
            <div>En Proceso: <span id="enProcesoCount"><?php echo $en_proceso; ?></span></div>
            <div>Finalizados: <span id="finalizadosCount"><?php echo $finalizados; ?></span></div>
        </div>
    </div>
</body>
</html>
