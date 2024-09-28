<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYSREST</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/list_clients.css"> <!-- Asegúrate de tener tu CSS -->
    <script src="https://use.fontawesome.com/bf66789927.js"></script>
</head>
<body>
    <div class="page-header">
        <div class="logo">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 9l9-6 9 6v9a2 2 0 0 1-2 2h-4v-6h-8v6H5a2 2 0 0 1-2-2V9z" fill="currentColor"/>
            </svg>
        </div>
        <nav>
            <ul class="admin-menu">
                <h4>ADMINISTRACIÓN</h4>
                <li><a href="#" onclick="loadClients()">Clientes</a></li>
                <li><a href="#">Usuarios</a></li>
                <li><a href="#">Servicios</a></li>
                <li><a href="#">Modulos</a></li>
                <h4>GESTIÓN TURNOS</h4>
                <li><a href="#">Generar Turnos</a></li>
                <li><a href="#">Atender Turnos</a></li>
                <h4>REPORTES</h4>
                <li><a href="#">Reportes</a></li>
                <h4>AJUSTES</h4>
                <li><a href="#">Configuración</a></li>
                <li><a href="#">Cerrar sesión</a></li>
            </ul>
        </nav>
    </div>

    <div class="page-content">
        <h1>Bienvenido</h1>
        <div class="search-and-user">
            <form>
                <input type="search" placeholder="Buscar...">
                <button type="submit">Buscar</button>
            </form>
            <div class="admin-profile">
                <span class="greeting">Hola, Admin</span>
                <svg width="30" height="30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="15" cy="15" r="15" fill="currentColor"/>
                </svg>
            </div>
        </div>

        <div id="client-info" style="margin-top: 20px;"></div> <!-- Contenedor para la lista de clientes -->
    </div>

    <footer class="page-footer">
        <span>&copy; 2024 Tu Empresa</span>
        <a href="#">Política de privacidad</a>
    </footer>

    <script src="js/list_clients.js"></script>
    <script>
        function loadClients() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'list_clients.php?ajax=true', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('client-info').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
