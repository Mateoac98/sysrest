<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Asegúrate de que el nombre del usuario está almacenado en la sesión
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Usuario'; // Valor por defecto

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYSREST</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="page-header">
        <div class="logo">
            <img src="images/sysrest.png" alt="Logo" class="logo-img">
        </div>
        <nav>
            <ul class="admin-menu">
                <h4>ADMINISTRACIÓN</h4>
                <li><a href="list_clients.php">Clientes</a></li>
                <li><a href="list_users.php">Usuarios</a></li>
                <li><a href="list_service_types.php">Servicios</a></li>
                <li><a href="list_modulos.php">Módulos</a></li>
                <h4>GESTIÓN TURNOS</h4>
                <li><a href="add_appointment.php">Generar Turnos</a></li>
                <li><a href="manage_appointments.php">Atender Turnos</a></li>
                <li><a href="#" id="reiniciarTurnos">Reiniciar Turnos</a></li>
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </div>

    <div class="page-content">
        <h1>BIENVENIDO</h1> <!-- Saludo al usuario -->
        <div class="search-and-user">
            <form>
                <input type="search" placeholder="Buscar...">
                <button type="submit">Buscar</button>
            </form>
            <div class="admin-profile">
                <span class="greeting">Hola, <?php echo $username; ?></span> <!-- Saludo adicional -->
                <div class="profile-icon"></div>
            </div>
        </div>

        <div class="statistics">
            <h3>Estadísticas de Turnos</h3>
            <p>Total Agendados: <span id="totalAgendados"></span></p>
            <p>Total Atendidos: <span id="totalAtendidos"></span></p>
            <p>Total Finalizados: <span id="totalFinalizados"></span></p>
            <h3>Tipos de Turnos</h3>
            <ul id="tiposTurnos"></ul>
            <h3>Servicios Utilizados</h3>
            <ul id="serviciosUtilizados"></ul>
        </div>
    </div>

    <footer class="page-footer">
        <div class="footer-content">
            <span>&copy; 2024 SYSREST</span>
            <a href="#">Política de privacidad</a>
        </div>
    </footer>
    </div>

    <script src="js/dashboard.js"></script> <!-- Incluye el script JavaScript -->
</body>
</html>
