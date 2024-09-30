<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Inicializar un array para los módulos
$modulos = array();

// Consultar todos los módulos en la base de datos
$sql = "SELECT modulo_id, nombre, estado FROM modulos";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    echo "Error en la consulta: " . $conn->error; 
    exit;
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $modulos[] = $row;
    }
}

// Verificar si se está solicitando a través de AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    // Solo genera el HTML de la tabla
    ?>
    <table class="listado" id="module-table">
        <thead>
            <tr>
                <th>Nombre del Módulo</th>
                <th>Estado</th>
                <th colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modulos as $modulo): ?>
                <tr data-id="<?php echo $modulo['modulo_id']; ?>">
                    <td><?php echo htmlspecialchars($modulo['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($modulo['estado']); ?></td>
                    <td class="icono">
                        <a href="#" onclick="editModule(<?php echo $modulo['modulo_id']; ?>)">
                            <span class="fa fa-pencil-square-o fa-2x"></span>
                        </a>
                    </td>
                    <td class="icono">
                        <a href="#" onclick="deleteModule(<?php echo $modulo['modulo_id']; ?>)">
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
    <title>Listado de Módulos</title>
    <link rel="stylesheet" href="css/list_modulos.css">
    <script src="https://use.fontawesome.com/bf66789927.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Listado de Módulos</h1>
        <div class="header-content">
            <button id="homeBtn" onclick="window.location.href='dashboard.php'" class="client-button">HOME</button>
            <button id="addModuleBtn" class="client-button">Agregar Nuevo Módulo</button>
        </div>
    </div>
    <div id="modules-list" style="margin-top: 20px;">
        <table class="listado" id="module-table">
            <thead>
                <tr>
                    <th>Nombre del Módulo</th>
                    <th>Estado</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Este tbody se llenará mediante la función fetchModules() -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar módulo -->
<div id="add-module-modal" style="display: none;">
    <div class="modal-content">
        <form id="new-module-form">
            <label for="nombre_modulo">Nombre del Módulo:</label>
            <input type="text" id="nombre_modulo" name="nombre_modulo" required>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>

            <button type="submit">Agregar Módulo</button>
            <button type="button" id="close-modal">Cancelar</button>
        </form>
    </div>
</div>

<script src="js/list_modulos.js"></script>
</body>
</html>
