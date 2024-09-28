<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'sysrest';
$usernameDB = 'root';
$passwordDB = 'nueva_contraseña';

$conn = new mysqli($host, $usernameDB, $passwordDB, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Actualizar contraseñas
$users = [
    ['nombre_usuario' => 'Admin', 'contraseña' => '1908'],
    ['nombre_usuario' => 'Jose Garcia', 'contraseña' => '1402'],
    ['nombre_usuario' => 'Martha Lopez', 'contraseña' => '0303']
];

foreach ($users as $user) {
    $hashedPassword = password_hash($user['contraseña'], PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET contraseña = '$hashedPassword' WHERE nombre_usuario = '{$user['nombre_usuario']}'";
    if ($conn->query($sql) === TRUE) {
        echo "Contraseña actualizada para {$user['nombre_usuario']}<br>";
    } else {
        echo "Error al actualizar la contraseña: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
