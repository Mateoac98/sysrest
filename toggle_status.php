<?php
session_start();
include 'includes/config.php';

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $cliente_id = $_GET['id'];
    $nuevo_estado = $_GET['estado'];

    // Actualizar el estado en la base de datos
    $sql = "UPDATE Clientes SET estado = ? WHERE cliente_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $cliente_id);

    if ($stmt->execute()) {
        // Redirigir de vuelta a la lista de clientes
        header("Location: dashboard.php?ajax=true");
        exit;
    } else {
        echo "Error al actualizar el estado: " . $conn->error;
    }
}
?>
