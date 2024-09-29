<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Inicializar un array para los usuarios
$usuarios = array();

// Consultar todos los usuarios en la base de datos
$sql = "SELECT u.usuario_id, u.nombre_usuario, u.modulo_id, t.nombre_servicio 
        FROM usuarios u 
        LEFT JOIN tiposservicio t ON u.tipo_servicio_id = t.tipo_servicio_id";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    echo "Error en la consulta: " . $conn->error; 
    exit;
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Verificar si se está solicitando a través de AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
    // Solo genera el HTML de la tabla
    ?>
    <table class="listado" id="user-table">
        <thead>
            <tr>
                <th>Nombre de Usuario</th>
                <th>Módulo</th>
                <th>Tipo de Servicio</th>
                <th colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr data-id="<?php echo $usuario['usuario_id']; ?>">
                    <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['modulo_id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre_servicio']); ?></td>
                    <td class="icono">
                        <a href="#" onclick="editUser(<?php echo $usuario['usuario_id']; ?>)">
                            <span class="fa fa-pencil-square-o fa-2x"></span>
                        </a>
                    </td>
                    <td class="icono">
                        <a href="#" onclick="deleteUser(<?php echo $usuario['usuario_id']; ?>)">
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
    <title>Listado de Usuarios</title>
    <link rel="stylesheet" href="css/list_users.css">
    <script src="https://use.fontawesome.com/bf66789927.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Listado de Usuarios</h1>
        <div class="header-content">
            <button id="homeBtn" onclick="window.location.href='dashboard.php'" class="client-button">HOME</button>
            <button id="addUserBtn" class="client-button">Agregar Nuevo Usuario</button>
        </div>
    </div>
    <div id="users-list" style="margin-top: 20px;">
        <table class="listado" id="user-table">
            <thead>
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Módulo</th>
                    <th>Tipo de Servicio</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Este tbody se llenará mediante la función fetchUsers() -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar usuario -->
<div id="add-user-modal" style="display: none;">
    <div class="modal-content">
        <form id="new-user-form">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" required>

            <label for="modulo_id">Módulo:</label>
            <select id="modulo_id" name="modulo_id" required>
                <option value="1">Módulo 1</option>
                <option value="2">Módulo 2</option>
                <option value="3">Módulo 3</option>
            </select>

            <label for="tipo_servicio_id">Tipo de Servicio:</label>
            <select id="tipo_servicio_id" name="tipo_servicio_id" required>
                <option value="1">Caja</option>
                <option value="2">Asesoría</option>
            </select>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Agregar Usuario</button>
            <button type="button" id="close-modal">Cancelar</button>
        </form>
    </div>
</div>

<script src="js/list_users.js"></script>
</body>
</html>
