function fetchClients() {
    fetch('clientes.php?ajax=true')
        .then(response => response.text())
        .then(data => {
            document.getElementById('client-table').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}

function editStatus(clienteId, estadoActual) {
    const estadoSelect = document.createElement('select');
    estadoSelect.innerHTML = `
        <option value="Activo" ${estadoActual === "Activo" ? "selected" : ""}>Activo</option>
        <option value="Inactivo" ${estadoActual === "Inactivo" ? "selected" : ""}>Inactivo</option>
    `;

    estadoSelect.addEventListener('change', function() {
        const nuevoEstado = estadoSelect.value;
        fetch(`update_status.php?id=${clienteId}&estado=${nuevoEstado}`, {
            method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            // Cambiar el estado visible a texto
            const cell = document.querySelector(`tr[data-id="${clienteId}"] td:nth-child(3)`);
            cell.innerHTML = nuevoEstado; // Actualiza el estado
        })
        .catch(error => console.error('Error:', error));
    });

    const cell = document.querySelector(`tr[data-id="${clienteId}"] td:nth-child(3)`);
    cell.innerHTML = ''; // Limpiar el contenido actual
    cell.appendChild(estadoSelect); // Agregar el select
}
