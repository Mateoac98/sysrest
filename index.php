<?php
session_start();

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'sysrest';
$usernameDB = 'root';
$passwordDB = 'nueva_contraseña';

$conn = new mysqli($host, $usernameDB, $passwordDB, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verifica que los campos no estén vacíos
    if (empty($username) || empty($password)) {
        $error = "Por favor, completa todos los campos.";
    } else {
        // Consulta a la base de datos para verificar el usuario
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verifica la contraseña
            if (password_verify($password, $user['contraseña'])) {
                $_SESSION['username'] = $username; // Guarda el nombre de usuario en la sesión
                $_SESSION['modulo_id'] = $user['modulo_id']; // Guarda el ID del módulo en la sesión
                header("Location: dashboard.php"); // Redirige a la página de dashboard
                exit();
            } else {
                $error = "Nombre de usuario o contraseña incorrectos.";
            }
        } else {
            $error = "Nombre de usuario o contraseña incorrectos.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form action="index.php" method="POST">
            <div class="user-box">
                <input type="text" name="username" required="">
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required="">
                <label>Password</label>
            </div>
            <?php if ($error): ?>
                <p style="color: red; text-align: center;"><?php echo $error; ?></p>
            <?php endif; ?>
            <button type="submit" class="login-button">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
