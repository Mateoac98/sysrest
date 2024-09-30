<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Inicializar un array para los tipos de servicio
$tipos_servicio = array();

// Consultar todos los tipos de servicio en la base de datos
$sql = "SELECT tipo_servicio_id, nombre_servicio, estado FROM tiposservicio";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    echo "Error en la consulta: " . $conn->error; 
    exit;
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tipos_servicio[] = $row;
    }
}

// Verificar si se está solicitando a través de AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    // Solo genera el HTML de la tabla
    ?>
    <table class="listado" id="service-table">
        <thead>
            <tr>
                <th>Nombre del Servicio</th>
                <th>Estado</th>
                <th colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tipos_servicio as $servicio): ?>
                <tr data-id="<?php echo $servicio['tipo_servicio_id']; ?>">
                    <td><?php echo htmlspecialchars($servicio['nombre_servicio']); ?></td>
                    <td><?php echo htmlspecialchars($servicio['estado']); ?></td>
                    <td class="icono">
                        <a href="#" onclick="editService(<?php echo $servicio['tipo_servicio_id']; ?>)">
                            <span class="fa fa-pencil-square-o fa-2x"></span>
                        </a>
                    </td>
                    <td class="icono">
                        <a href="#" onclick="deleteService(<?php echo $servicio['tipo_servicio_id']; ?>)">
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
    <title>Listado de Tipos de Servicio</title>
    <link rel="stylesheet" href="css/list_service_types.css">
    <script src="https://use.fontawesome.com/bf66789927.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Listado de Tipos de Servicio</h1>
        <div class="header-content">
            <button id="homeBtn" onclick="window.location.href='dashboard.php'" class="client-button">HOME</button>
            <button id="addServiceBtn" class="client-button">Agregar Nuevo Tipo de Servicio</button>
        </div>
    </div>
    <div id="services-list" style="margin-top: 20px;">
        <table class="listado" id="service-table">
            <thead>
                <tr>
                    <th>Nombre del Servicio</th>
                    <th>Estado</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Este tbody se llenará mediante la función fetchServices() -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar tipo de servicio -->
<div id="add-service-modal" style="display: none;">
    <div class="modal-content">
        <form id="new-service-form">
            <label for="nombre_servicio">Nombre del Servicio:</label>
            <input type="text" id="nombre_servicio" name="nombre_servicio" required>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>

            <button type="submit">Agregar Tipo de Servicio</button>
            <button type="button" id="close-modal">Cancelar</button>
        </form>
    </div>
</div>

<script src="js/list_service_types.js"></script>
</body>
</html>
