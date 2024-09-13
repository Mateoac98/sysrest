<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Lista de Clientes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Tipo de Documento</th>
                <th>Número de Documento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Conexión a la base de datos y consulta de clientes
            include 'includes/config.php';
            $query = "SELECT * FROM Clientes";
            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['cliente_ID']}</td>";
                echo "<td>{$row['nombre']} {$row['apellido']}</td>";
                echo "<td>{$row['tipo_documento']}</td>";
                echo "<td>{$row['numero_documento']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>

