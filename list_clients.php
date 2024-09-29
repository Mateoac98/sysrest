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

// Verificar si se está solicitando a través de AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    // Solo genera el HTML de la tabla
    ?>
    <table class="listado" id="client-table">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr data-id="<?php echo $cliente['cliente_id']; ?>">
                    <td><?php echo htmlspecialchars($cliente['numero_documento']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['estado']); ?></td>
                    <td class="icono">
                        <a href="#" onclick="editStatus(<?php echo $cliente['cliente_id']; ?>, '<?php echo htmlspecialchars($cliente['estado']); ?>')">
                            <span class="fa fa-pencil-square-o fa-2x"></span>
                        </a>
                    </td>
                    <td class="icono">
                        <a href="#" onclick="deleteClient(<?php echo $cliente['cliente_id']; ?>)">
                            <span class="fa fa-trash fa-2x"></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    exit; // Detener ejecución para no mostrar el resto del HTML
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
        <div class="header-content">
        <button id="homeBtn" onclick="window.location.href='dashboard.php'" class="client-button">HOME</button>
         <button id="addClientBtn" class="client-button">Agregar Nuevo Cliente</button>
        </div>
    </div>
    <div id="clients-list" style="margin-top: 20px;">
        <!-- Aquí se cargará la tabla de clientes mediante AJAX -->
        <table class="listado" id="client-table">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Este tbody se llenará mediante la función fetchClients() -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar cliente -->
<div id="add-client-modal" style="display:none;">
    <div class="modal-content">
        <span id="close-modal" class="close">&times;</span>
        <form id="new-client-form">
            <label for="tipo_documento">Tipo de Documento:</label>
            <select id="tipo_documento">
                <option value="CC">CC</option>
                <option value="TI">TI</option>
            </select>
            <br>
            <label for="nombre_completo">Nombre Completo:</label>
            <input type="text" id="nombre_completo" required>
            <br>
            <label for="numero_documento">Número de Documento:</label>
            <input type="text" id="numero_documento" required>
            <br>
            <button type="submit">Agregar Cliente</button>
        </form>
    </div>
</div>

<script src="js/list_clients.js"></script>
</body>
</html>
