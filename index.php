<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agendamiento de Turnos - SYSREST</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <div class="page" id="page1">
            <div class="header">
                <h1>BIENVENIDO</h1>
            </div>
            <div class="image-container">
                <img src="images/logo.png" alt="Imagen de SYSREST">
            </div>
            <button id="ingresarBtn">Ingresar</button>
            <div class="currentDateTimeContainer">
                <div class="currentDateTimeBox">
                    <p>HORA:</p>
                    <span id="currentTime"></span>
                </div>
                <div class="currentDateTimeBox">
                    <p>FECHA:</p>
                    <span id="currentDate"></span>
                </div>
            </div>
        </div>
        <div class="page" id="page2" style="display: none;">
            <h1>SYSREST</h1>
            <div class="form">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required>

                <label for="documento">Tipo de Documento:</label>
                <select id="documento" name="documento" required>
                    <option value="na"></option>
                    <option value="cc">Cédula de Ciudadanía</option>
                    <option value="ti">Tarjeta de Identidad</option>
                    <option value="ce">Cédula de Extranjería</option>
                    <option value="pa">Pasaporte</option>
                </select>

                <label for="numeroDocumento">Número de Documento:</label>
                <input type="text" id="numeroDocumento" name="numeroDocumento" required>

                <label for="tipoServicio">Tipo de Servicio:</label>
                <select id="tipoServicio" name="tipoServicio" required>
                    <option value="na"></option>
                    <option value="au">AUTORIZACIONES</option>
                    <option value="va">VACUNACIÓN</option>
                    <option value="la">LABORATORIO</option>
                    <option value="tr">TRAMITES</option>
                </select>

                <label for="tipoTurno">Tipo de Turno:</label>
                <div class="button-container">
                    <button id="generalBtn" onclick="showWarning('general')">GENERAL</button>
                    <button id="preferencialBtn" onclick="showWarning('preferencial')">PREFERENCIAL</button>
                </div>

                <div id="warningMessage" style="color: red; display: none;">Por favor, complete todos los campos requeridos.</div>
            </div>
            <div class="currentDateTimeContainer">
                <div class="currentDateTimeBox">
                    <p>HORA:</p>
                    <span id="currentTimeForm"></span>
                </div>
                <div class="currentDateTimeBox">
                    <p>FECHA:</p>
                    <span id="currentDateForm"></span>
                </div>
            </div>
        </div>
        <div class="page" id="page3" style="display: none;">
            <h1>SYSREST</h1>
            <div class="turno-container">
                <div class="turno-info">
                    <p>Su turno es:</p>
                    <div class="numero-turno">
                        <!-- Aquí se mostrará el número de turno -->
                    </div>
                </div>
                <div class="mensaje-advertencia">
                    <p>¿Desea agendar otro turno?</p>
                    <button id="siBtn">Sí</button>
                    <button id="noBtn">No</button>
                </div>
            </div>
            <div class="currentDateTimeContainer">
                <div class="currentDateTimeBox">
                    <p>FECHA:</p>
                    <span id="currentDatePage3"></span>
                </div>
                <div class="currentDateTimeBox">
                    <p>HORA:</p>
                    <span id="currentTimePage3"></span>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>© SYSREST 2024</p>
        </div>
    </div>
    <script src="./js/script.js"></script>
</body>
</html>


