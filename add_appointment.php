<?php
date_default_timezone_set('America/Bogota'); // Configurar la zona horaria a Colombia
include 'includes/header.php';
?>
<link rel="stylesheet" href="css/add_appointment.css"> <!-- Añadir esta línea -->

<div class="container">
    
    <div class="form-section">
        <h1>Asignación de turnos</h1>
        <form action="process_appointment.php" method="POST">
            <!-- Campo para el ID del Cliente -->
            <label for="cliente_id">Cliente ID:</label>
            <input type="number" id="cliente_id" name="cliente_id" required>

            <!-- Campo para el Tipo de Servicio -->
            <label for="tipo_servicio_ID">Tipo de Servicio:</label>
            <select id="tipo_servicio_ID" name="tipo_servicio_ID" required>
                <option value="">Seleccione un servicio</option>
                <option value="1">Caja</option>
                <option value="2">Asesoría</option>
            </select>

            <!-- Campo para el Tipo de Turno -->
            <label for="tipo_turno_ID">Tipo de Turno:</label>
            <select id="tipo_turno_ID" name="tipo_turno_ID" required>
                <option value="">Seleccione un tipo de turno</option>
                <option value="1">General</option>
                <option value="2">Preferencial</option>
            </select>

            <!-- Campo para la Fecha (con la fecha actual) -->
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>

            <!-- Campo para la Hora (con la hora actual) -->
            <label for="hora">Hora:</label>
            <input type="time" id="hora" name="hora" value="<?php echo date('H:i'); ?>" required>

            <!-- Campo para el Estado del Turno -->
            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="Pendiente" required>

            <!-- Campo para Asignar Personal -->
            <label for="personal_ID">Asignar Personal:</label>
            <select id="personal_ID" name="personal_ID" required>
                <option value="">Seleccione personal</option>
                <?php
                // Obtener la lista de personal disponible desde la base de datos
                include 'includes/config.php'; // Conexión a la base de datos
                $query = "SELECT * FROM Personal";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['personal_id']}'>{$row['nombre_completo']} - {$row['cargo']}</option>";
                }
                ?>
            </select>

            <!-- Botón para agregar la cita -->
            <button type="submit">Solicitar Turno</button>
        </form>
    </div>
    
    <div class="ticket-section">
        <h2>SYSREST</h2>
        <?php if (isset($_GET['turno_id']) && isset($_GET['nombre_cliente'])): ?>
            <div class="ticket">
                <p><strong>Turno ID:</strong> <?php echo htmlspecialchars($_GET['turno_id']); ?></p>
                <p><strong>Nombre del Cliente:</strong> <?php echo htmlspecialchars($_GET['nombre_cliente']); ?></p>
                <p class="warning">Este turno es personal y no es transferible.</p>
            </div>
        <?php else: ?>
            <p>No hay información de ticket disponible.</p>
        <?php endif; ?>
    </div>
</div>

<script src="js/add_appointment.js"></script> <!-- Ruta correcta para el archivo JS -->
<?php include 'includes/footer.php'; ?>
