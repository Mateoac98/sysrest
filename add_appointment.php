<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Agregar Cita</h1>
    <form action="process_appointment.php" method="POST">
        <label for="cliente_ID">Cliente ID:</label>
        <input type="number" id="cliente_ID" name="cliente_ID" required>
        
        <label for="tipo_servicio_ID">Tipo de Servicio:</label>
        <select id="tipo_servicio_ID" name="tipo_servicio_ID" required>
            <option value="1">Caja</option>re
            <option value="2">Asesoría</option>
        </select>

        <label for="tipo_turno_ID">Tipo de Turno xxx:</label>
        <select id="tipo_turno_ID" name="tipo_turno_ID" required>
            <option value="1">General</option>
            <option value="2">Preferencial</option>
        </select>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required>

        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" required>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required>

        <button type="submit">Agregar Cita</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
