<?php include 'includes/header.php'; ?>
<div class="container">
    <h1>Turnos Agendados</h1>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Tipo de Servicio</th>
                <th>Tipo de Turno</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Modulo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Incluir la conexión a la base de datos desde config.php
            include 'includes/config.php';

            // Verificar si la conexión a la base de datos está abierta
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Consulta para obtener los turnos agendados
            $query = "SELECT * FROM TurnosAgendados";
            $result = mysqli_query($conn, $query);

            // Verificar si la consulta fue exitosa
            if (!$result) {
                die("Error en la consulta: " . mysqli_error($conn));  // Mostrar el error de la consulta
            }

            // Recorrer los resultados de la consulta
            while ($row = mysqli_fetch_assoc($result)) {
                // Verificar si cliente_id está definido y es válido
                if (!isset($row['cliente_id']) || empty($row['cliente_id'])) {
                    echo "<tr><td colspan='7'>Cliente no disponible</td></tr>";
                    continue;
                }

                // Obtener el nombre del cliente
                $clienteQuery = "SELECT nombre_completo FROM Clientes WHERE cliente_id = {$row['cliente_id']}";
                $clienteResult = mysqli_query($conn, $clienteQuery);
                if (!$clienteResult) {
                    die("Error en la consulta de clientes: " . mysqli_error($conn));
                }
                $cliente = mysqli_fetch_assoc($clienteResult)['nombre_completo'] ?? 'Cliente no encontrado';

                // Obtener el nombre del servicio
                $tipoServicioQuery = "SELECT nombre_servicio FROM TiposServicio WHERE tipo_servicio_ID = {$row['tipo_servicio_id']}";
                $tipoServicioResult = mysqli_query($conn, $tipoServicioQuery);
                if (!$tipoServicioResult) {
                    die("Error en la consulta de servicios: " . mysqli_error($conn));
                }
                $tipoServicio = mysqli_fetch_assoc($tipoServicioResult)['nombre_servicio'];

                // Obtener el nombre del turno
                $tipoTurnoQuery = "SELECT nombre_turno FROM TiposTurno WHERE tipo_turno_ID = {$row['tipo_turno_id']}";
                $tipoTurnoResult = mysqli_query($conn, $tipoTurnoQuery);
                if (!$tipoTurnoResult) {
                    die("Error en la consulta de turnos: " . mysqli_error($conn));
                }
                $tipoTurno = mysqli_fetch_assoc($tipoTurnoResult)['nombre_turno'];

                // Obtener el nombre del personal asignado
                $personalQuery = "SELECT nombre FROM Modulos WHERE modulo_id = {$row['modulo_id']}"; // Cambiar a modulo_id
                $personalResult = mysqli_query($conn, $personalQuery);
                if (!$personalResult) {
                    die("Error en la consulta de personal: " . mysqli_error($conn));
                }
                $personal = mysqli_fetch_assoc($personalResult)['nombre'] ?? 'Personal no encontrado'; // Cambiar a nombre

                // Mostrar los datos en la tabla
                echo "<tr>";
                echo "<td>{$cliente}</td>";
                echo "<td>{$tipoServicio}</td>";
                echo "<td>{$tipoTurno}</td>";
                echo "<td>{$row['fecha']}</td>";
                echo "<td>{$row['hora']}</td>";
                echo "<td>{$row['estado']}</td>";
                echo "<td>{$personal}</td>"; // Mostrar el personal asignado
                echo "</tr>";
            }

            ?>
        </tbody>
    </table>
</div>
<?php
// Cerrar la conexión al final del archivo
if (isset($conn) && $conn->ping()) {
    mysqli_close($conn);
}
?>
<?php include 'includes/footer.php'; ?>
