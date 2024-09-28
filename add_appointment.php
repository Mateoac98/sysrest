<?php 
date_default_timezone_set('America/Bogota'); // Configurar la zona horaria a Colombia
include 'includes/header.php';
?>
<link rel="stylesheet" href="css/add_appointment.css"> <!-- Añadir esta línea -->

<div class="container">
    <div class="form-section">
        <h1>Asignación de turnos</h1>
        <form action="process_appointment.php" method="POST" onsubmit="updateDateTime();">
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

            <!-- Campo oculto para la Fecha -->
            <input type="hidden" id="fecha" name="fecha">
            <!-- Campo oculto para la Hora -->
            <input type="hidden" id="hora" name="hora">

            <div class="current-time">
                <p>Fecha: <span id="currentDate">00/00/0000</span></p>
                <p>Hora: <span id="currentTime">00:00:00</span></p> 
            </div>

            <!-- Botón para agregar la cita -->
            <button type="submit">Solicitar Turno</button>
        </form>
    </div>
    
    <div class="ticket-section">
        <h2>SYSREST</h2>
        <?php if (isset($_GET['turno_id']) && isset($_GET['nombre_cliente']) && isset($_GET['fecha']) && isset($_GET['hora'])): ?>
            <div class="ticket">
                <p><strong>Turno ID:</strong> <?php echo htmlspecialchars($_GET['turno_id']); ?></p>
                <p><strong>Nombre del Cliente:</strong> <?php echo htmlspecialchars($_GET['nombre_cliente']); ?></p>
                <p><strong>Fecha:</strong> <?php echo htmlspecialchars($_GET['fecha']); ?></p>
                <p><strong>Hora:</strong> <?php echo htmlspecialchars($_GET['hora']); ?></p>
                <p class="warning">Este turno es personal y no es transferible.</p>
            </div>
        <?php else: ?>
            <p>No hay información de ticket disponible.</p>
        <?php endif; ?>
    </div>

    <!-- Sección del Turnero -->
    <div class="turnero-section">
        <h2>Turnero</h2>
        <div class="turno-info">
            <p><strong>Turno ID:</strong> <span id="turnoId">-</span></p>
            <p><strong>Módulo de Atención:</strong> <span id="moduloAtencion">-</span></p>
        </div>
        <div class="cancel-section">
            <button id="cancelTurno" style="display: none;">Cancelar Turno</button>
        </div>
    </div>

</div>

<script src="js/add_appointment.js"></script> <!-- Ruta correcta para el archivo JS -->
<?php include 'includes/footer.php'; ?>
