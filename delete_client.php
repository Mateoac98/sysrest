<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
include 'includes/config.php';

// Verificar que se ha recibido un ID
if (isset($_GET['id'])) {
    $clienteId = intval($_GET['id']);

    // Eliminar el cliente
    $stmt = $conn->prepare("DELETE FROM Clientes WHERE cliente_id = ?");
    $stmt->bind_param("i", $clienteId);

    if ($stmt->execute()) {
        // Reordenar los IDs
        $conn->query("SET @new_id = 0");
        $conn->query("UPDATE Clientes SET cliente_id = (@new_id := @new_id + 1)");

        // También puedes optar por reiniciar el auto incremento si es necesario
        $conn->query("ALTER TABLE Clientes AUTO_INCREMENT = 1");

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
}

$stmt->close();
$conn->close();
?>
