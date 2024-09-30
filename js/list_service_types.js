function fetchServices() {
    fetch('list_service_types.php?ajax=true')
        .then(response => response.text())
        .then(data => {
            const serviceTable = document.getElementById('service-table');
            if (serviceTable) {
                serviceTable.innerHTML = data;  
                setupModalListener();
                setupFormListener(); 
                setupEditListeners();
            } else {
                console.error("Error: No se encontró el elemento con id 'service-table'.");
            }
        })
        .catch(error => console.error('Error:', error));
}

function setupModalListener() {
    const addServiceBtn = document.getElementById('addServiceBtn');
    const modal = document.getElementById('add-service-modal');
    const closeModal = document.getElementById('close-modal');

    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function() {
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
    const form = document.getElementById('new-service-form');
    const modal = document.getElementById('add-service-modal');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const nombreServicio = document.getElementById('nombre_servicio').value;
            const estado = document.getElementById('estado').value.charAt(0).toUpperCase() + document.getElementById('estado').value.slice(1).toLowerCase();

            const addServiceBtn = form.querySelector('button[type="submit"]');
            addServiceBtn.disabled = true; // Deshabilitar el botón

            fetch('add_service.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre_servicio: nombreServicio,
                    estado: estado
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Servicio agregado con éxito');
                    fetchServices(); 
                } else {
                    alert('Error al agregar servicio: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                modal.style.display = 'none';
                addServiceBtn.disabled = false; // Volver a habilitar el botón
            });
        });
    }
}

function setupEditListeners() {
    const editButtons = document.querySelectorAll('.fa-pencil-square-o');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.closest('tr').dataset.id;
            editService(serviceId);
        });
    });
}

function editService(serviceId) {
    const row = document.querySelector(`tr[data-id="${serviceId}"]`);
    const cells = row.querySelectorAll('td');

    const nombreServicio = cells[0].innerText;
    const estado = cells[1].innerText;

    cells[0].innerHTML = nombreServicio; // No editable
    cells[1].innerHTML = `
        <select class="editable">
            <option value="Activo" ${estado === 'Activo' ? 'selected' : ''}>Activo</option>
            <option value="Inactivo" ${estado === 'Inactivo' ? 'selected' : ''}>Inactivo</option>
        </select>
    `;

    const saveButton = document.createElement('button');
    saveButton.textContent = 'Guardar';
    saveButton.onclick = function() {
        saveService(serviceId, row);
    };
    saveButton.classList.add('save-button');
    cells[3].innerHTML = '';
    cells[3].appendChild(saveButton);
}

function saveService(serviceId, row) {
    const inputs = row.querySelectorAll('.editable');
    const estado = inputs[0].value;

    fetch('update_service.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            service_id: serviceId,
            estado: estado
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Servicio actualizado con éxito');
            fetchServices(); 
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteService(serviceId) {
    if (confirm('¿Estás seguro de que deseas eliminar este tipo de servicio?')) {
        fetch(`delete_service.php?id=${serviceId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Servicio eliminado con éxito');
                fetchServices(); 
            } else {
                alert('Error al eliminar servicio: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Inicialización
document.addEventListener("DOMContentLoaded", function() {
    fetchServices(); 
});
