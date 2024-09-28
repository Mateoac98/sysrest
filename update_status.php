<?php
include 'includes/config.php';

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $clienteId = intval($_GET['id']);
    $nuevoEstado = $conn->real_escape_string($_GET['estado']);
    
    // Validar estado
    if ($nuevoEstado !== 'Activo' && $nuevoEstado !== 'Inactivo') {
        echo "Estado inválido.";
        exit;
    }

    $sql = "UPDATE Clientes SET estado = '$nuevoEstado' WHERE cliente_id = $clienteId";

    if ($conn->query($sql) === TRUE) {
        echo "Estado actualizado correctamente.";
    } else {
        echo "Error al actualizar estado: " . $conn->error;
    }
} else {
    echo "Datos inválidos.";
}

$conn->close();
?>
