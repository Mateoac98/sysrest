<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Inicializar un array para los clientes
$clientes = array();

// Consultar todos los clientes en la base de datos
$sql = "SELECT cliente_id, numero_documento, nombre_completo, estado FROM Clientes";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    echo "Error en la consulta: " . $conn->error; 
    exit;
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

if (isset($_GET['ajax'])) {
    echo '<h1>Listado de Clientes</h1>';
    echo '<table class="listado">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Documento</th>';
    echo '<th>Nombre</th>';
    echo '<th>Estado</th>';
    echo '<th colspan="2">Opciones</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($clientes as $cliente) {
        echo '<tr data-id="' . $cliente['cliente_id'] . '">';
        echo '<td>' . htmlspecialchars($cliente['numero_documento']) . '</td>';
        echo '<td>' . htmlspecialchars($cliente['nombre_completo']) . '</td>';
        echo '<td>' . htmlspecialchars($cliente['estado']) . '</td>';
        echo '<td class="icono"><a href="#" onclick="editStatus(' . $cliente['cliente_id'] . ', \'' . htmlspecialchars($cliente['estado']) . '\')"><span class="fa fa-pencil-square-o fa-2x"></span></a></td>';
        echo '<td class="icono"><a href="#"><span class="fa fa-trash fa-2x"></span></a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="css/list_clients.css">
    <script src="https://use.fontawesome.com/bf66789927.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Listado de Clientes</h1>
        <button id="add-client-btn">Agregar Nuevo Cliente</button>
    </div>
    <div id="client-table">
        <!-- Aquí se cargarán los clientes -->
    </div>
</div>

<!-- Modal para agregar nuevo cliente -->
<div id="add-client-modal" style="display:none;">
    <div class="modal-content">
        <span id="close-modal">&times;</span>
        <h2>Agregar Nuevo Cliente</h2>
        <form id="new-client-form">
            <input type="text" id="nombre_completo" placeholder="Nombre Completo" required>
            <input type="text" id="numero_documento" placeholder="Número de Documento" required>
            <select id="tipo_documento" required>
                <option value="DNI">DNI</option>
                <option value="RUC">RUC</option>
                <!-- Agregar más tipos de documento si es necesario -->
            </select>
            <button type="submit">Agregar Cliente</button>
        </form>
    </div>
</div>

<script src="js/list_clients.js" defer></script>
</body>
</html>