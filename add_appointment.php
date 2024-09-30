<?php 
date_default_timezone_set('America/Bogota'); // Configurar la zona horaria a Colombia
include 'includes/header.php';
?>
<link rel="stylesheet" href="css/add_appointment.css"> <!-- Añadir esta línea -->

<div class="container">
    <div class="form-section">
        <h1>Asignación de turnos</h1>
        <form action="process_appointment.php" method="POST" onsubmit="updateDateTime();">
            <!-- Campo para el Tipo de Documento -->
            <label for="tipo_documento">Tipo de Documento:</label>
            <select name="tipo_documento" id="tipo_documento" required>
                <option value="">Seleccione</option>
                <option value="CC">CC</option>
                <option value="TI">TI</option>
                <option value="PPT">PPT</option>
                <!-- Agrega otros tipos de documento según sea necesario -->
            </select>
            
            <!-- Campo para el Número de Documento -->
            <label for="numero_documento">Número de Documento:</label>
            <input type="text" id="numero_documento" name="numero_documento" required>

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

            <div class="button-container">
                <button id="agendarTurno">Agendar Turno</button>
                <button id="cancelarTurno">Cancelar Turno</button>
            </div>

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

</div>

<script src="js/add_appointment.js"></script> <!-- Ruta correcta para el archivo JS -->
<?php include 'includes/footer.php'; ?>
