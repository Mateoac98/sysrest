<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php'; // Incluye el archivo de configuración

// Inicializar un array para los clientes
$clientes = array();

// Consultar todos los clientes en la base de datos
$sql = "SELECT cliente_id, nombre_completo, tipo_documento, numero_documento FROM Clientes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Recorrer los resultados y almacenarlos en un array
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

// Verificar si la solicitud es una llamada AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    // Retornar los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($clientes);
    exit;
}

// Si no es una solicitud AJAX, mostrar la lista en HTML
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="css/list_clients.css"> <!-- Añadir esta línea -->
</head>
<body>

<div class="container">
    <h1>Nuestros Clientes</h1>
    <div class="client-list">
        <?php foreach ($clientes as $cliente): ?>
            <div class="client-item">
                <div class="client-info"><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($cliente['nombre_completo']); ?></div>
                <div class="client-info"><strong>ID Cliente:</strong> <?php echo htmlspecialchars($cliente['cliente_id']); ?></div>
                <div class="client-info"><strong>Tipo de Documento:</strong> <?php echo htmlspecialchars($cliente['tipo_documento']); ?></div>
                <div class="client-info"><strong>Número de Documento:</strong> <?php echo htmlspecialchars($cliente['numero_documento']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="footer">
        <p>© SYSREST 2024 Todos los derechos reservados.</p>
    </div>
</div>

</body>
</html>

