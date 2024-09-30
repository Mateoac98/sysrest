<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli('localhost', 'root', 'nueva_contraseña', 'sysrest');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Contar turnos agendados
$resultAgendados = $conn->query("SELECT COUNT(*) AS total_agendados FROM turnosagendados WHERE estado = 'Agendado'");
$totalAgendados = $resultAgendados->fetch_assoc()['total_agendados'];

// Contar turnos atendidos
$resultAtendidos = $conn->query("SELECT COUNT(*) AS total_atendidos FROM turnosagendados WHERE estado = 'En Proceso' OR estado = 'Finalizado'");
$totalAtendidos = $resultAtendidos->fetch_assoc()['total_atendidos'];

// Contar turnos finalizados
$resultFinalizados = $conn->query("SELECT COUNT(*) AS total_finalizados FROM turnosagendados WHERE estado = 'Finalizado'");
$totalFinalizados = $resultFinalizados->fetch_assoc()['total_finalizados'];

// Contar tipos de turnos
$resultTiposTurnos = $conn->query("SELECT t.nombre_turno, COUNT(*) AS count FROM turnosagendados ta JOIN tiposturno t ON ta.tipo_turno_id = t.tipo_turno_id GROUP BY t.nombre_turno");
$tiposTurnos = [];
while ($row = $resultTiposTurnos->fetch_assoc()) {
    $tiposTurnos[] = [
        'nombre_turno' => $row['nombre_turno'],
        'cantidad' => $row['count']
    ];
}

// Contar servicios utilizados
$resultServicios = $conn->query("SELECT ts.nombre_servicio, COUNT(*) AS count FROM turnosagendados ta JOIN tiposservicio ts ON ta.tipo_servicio_id = ts.tipo_servicio_id GROUP BY ts.nombre_servicio");
$serviciosUtilizados = [];
while ($row = $resultServicios->fetch_assoc()) {
    $serviciosUtilizados[] = [
        'nombre_servicio' => $row['nombre_servicio'],
        'cantidad' => $row['count']
    ];
}

// Respuesta JSON
echo json_encode([
    'total_agendados' => $totalAgendados,
    'total_atendidos' => $totalAtendidos,
    'total_finalizados' => $totalFinalizados,
    'tipos_turnos' => $tiposTurnos,
    'servicios_utilizados' => $serviciosUtilizados,
]);

$conn->close();
?>
