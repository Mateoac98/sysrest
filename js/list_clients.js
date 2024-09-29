function fetchClients() {
    fetch('list_clients.php?ajax=true')
        .then(response => response.text())
        .then(data => {
            const clientTable = document.getElementById('client-table');
            if (clientTable) {
                clientTable.innerHTML = data;  
                setupModalListener();
                setupFormListener(); 
            } else {
                console.error("Error: No se encontró el elemento con id 'client-table'.");
            }
        })
        .catch(error => console.error('Error:', error));
}

function setupModalListener() {
    const addClientBtn = document.getElementById('addClientBtn');
    const modal = document.getElementById('add-client-modal');
    const closeModal = document.getElementById('close-modal');

    if (addClientBtn) {
        addClientBtn.addEventListener('click', function() {
            modal.style.display = 'block';
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}

function setupFormListener() {
    const form = document.getElementById('new-client-form');
    const modal = document.getElementById('add-client-modal');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const nombreCompleto = document.getElementById('nombre_completo').value;
            const numeroDocumento = document.getElementById('numero_documento').value;
            const tipoDocumento = document.getElementById('tipo_documento').value;

            fetch('add_client.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre_completo: nombreCompleto,
                    numero_documento: numeroDocumento,
                    tipo_documento: tipoDocumento,
                    estado: 'Activo'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cliente agregado con éxito');
                    fetchClients(); 
                } else {
                    alert('Error al agregar cliente: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));

            modal.style.display = 'none';
        });
    }
}

function deleteClient(clienteId) {
    if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
        fetch(`delete_client.php?id=${clienteId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cliente eliminado con éxito');
                fetchClients(); // Actualiza la lista de clientes
            } else {
                alert('Error al eliminar cliente: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
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
            const cell = document.querySelector(`tr[data-id="${clienteId}"] td:nth-child(3)`);
            cell.innerHTML = nuevoEstado;
        })
        .catch(error => console.error('Error:', error));
    });

    const cell = document.querySelector(`tr[data-id="${clienteId}"] td:nth-child(3)`);
    cell.innerHTML = '';
    cell.appendChild(estadoSelect);
}

document.addEventListener("DOMContentLoaded", function() {
    fetchClients(); 
});
