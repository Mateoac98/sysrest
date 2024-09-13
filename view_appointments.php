<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Ver Citas</h1>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Tipo de Servicio</th>
                <th>Tipo de Turno</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conexión a la base de datos y consulta de citas
            include 'includes/config.php';
            $query = "SELECT * FROM TurnosAgendados";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                // Obtén nombres en lugar de IDs si es necesario
                $clienteQuery = "SELECT nombre FROM Clientes WHERE cliente_ID = {$row['cliente_ID']}";
                $clienteResult = mysqli_query($conn, $clienteQuery);
                $cliente = mysqli_fetch_assoc($clienteResult)['nombre'];

                $tipoServicioQuery = "SELECT nombre_servicio FROM TiposServicio WHERE tipo_servicio_ID = {$row['tipo_servicio_ID']}";
                $tipoServicioResult = mysqli_query($conn, $tipoServicioQuery);
                $tipoServicio = mysqli_fetch_assoc($tipoServicioResult)['nombre_servicio'];

                $tipoTurnoQuery = "SELECT nombre_turno FROM TiposTurno WHERE tipo_turno_ID = {$row['tipo_turno_ID']}";
                $tipoTurnoResult = mysqli_query($conn, $tipoTurnoQuery);
                $tipoTurno = mysqli_fetch_assoc($tipoTurnoResult)['nombre_turno'];

                echo "<tr>";
                echo "<td>{$cliente}</td>";
                echo "<td>{$tipoServicio}</td>";
                echo "<td>{$tipoTurno}</td>";
                echo "<td>{$row['fecha']}</td>";
                echo "<td>{$row['hora']}</td>";
                echo "<td>{$row['estado']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>

