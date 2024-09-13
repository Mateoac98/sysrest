function showWarning(message) {
    const warningMessage = document.getElementById('warningMessage');
    warningMessage.textContent = message;
    warningMessage.style.display = 'block';
}

function hideWarning() {
    const warningMessage = document.getElementById('warningMessage');
    warningMessage.textContent = '';
    warningMessage.style.display = 'none';
}

function resetFields() {
    document.getElementById("nombre").value = "";
    document.getElementById("documento").value = "na";
    document.getElementById("numeroDocumento").value = "";
    document.getElementById("tipoServicio").value = "na";
}

function updateDateTime() {
    const currentDateTime = new Date();

    const currentTime = currentDateTime.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    const currentDate = currentDateTime.toLocaleDateString('es-ES');

    document.getElementById("currentTime").textContent = currentTime;
    document.getElementById("currentDate").textContent = currentDate;

    document.getElementById("currentTimeForm").textContent = currentTime;
    document.getElementById("currentDateForm").textContent = currentDate;

    document.getElementById("currentDatePage3").textContent = currentDate;
    document.getElementById("currentTimePage3").textContent = currentTime;
}

function goToPage2() {
    resetFields();
    document.getElementById("page1").style.display = "none";
    document.getElementById("page2").style.display = "block";
}

setInterval(updateDateTime, 1000);
updateDateTime();

const ingresarBtn = document.getElementById("ingresarBtn");
ingresarBtn.addEventListener("click", goToPage2);

var numeroDeTurnoActual = 0;

function redirectToPage3(tipoTurno) {
    const nombre = document.getElementById("nombre").value;
    const documento = document.getElementById("documento").value;
    const numeroDocumento = document.getElementById("numeroDocumento").value;
    const tipoServicio = document.getElementById("tipoServicio").value;

    if (!nombre || documento === "na" || !numeroDocumento || tipoServicio === "na") {
        showWarning("Por favor, llene todos los campos requeridos antes de continuar.");
        return;
    } else {
        hideWarning();
    }
    numeroDeTurnoActual++;
    if (numeroDeTurnoActual <= 100) {
        document.getElementById('page2').style.display = 'none';
        document.getElementById('page3').style.display = 'block';
        document.querySelector('.numero-turno').textContent = pad(numeroDeTurnoActual, 3);
    } else {
        alert('Lo sentimos, se han agotado los turnos disponibles.');
        numeroDeTurnoActual = 0;
    }
}

document.getElementById('siBtn').addEventListener('click', function() {
    redirectToPage2();
});

document.getElementById('noBtn').addEventListener('click', function() {
    alert('Gracias por usar SYSREST. El proceso ha finalizado.');
    document.getElementById('page3').style.display = 'none';
    document.getElementById('page1').style.display = 'block';
});

function redirectToPage2() {
    resetFields();
    document.getElementById('page3').style.display = 'none';
    document.getElementById('page2').style.display = 'block';
}

function pad(number, length) {
    return (number + '').padStart(length, '0');
}

document.getElementById('generalBtn').addEventListener('click', function() {
    redirectToPage3('page3');
});

document.getElementById('preferencialBtn').addEventListener('click', function() {
    redirectToPage3('page3');
});
