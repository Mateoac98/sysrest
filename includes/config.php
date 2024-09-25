<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'sysrest';
$username = 'root';
$password = 'nueva_contraseña';

// Crear la conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres para evitar problemas con UTF-8
$conn->set_charset("utf8");
?>

