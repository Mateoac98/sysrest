function fetchModules() {
    fetch('list_modulos.php?ajax=true')
        .then(response => response.text())
        .then(data => {
            const moduleTable = document.getElementById('module-table');
            if (moduleTable) {
                moduleTable.innerHTML = data;  
                setupModalListener();
                setupFormListener(); 
                setupEditListeners();
            } else {
                console.error("Error: No se encontró el elemento con id 'module-table'.");
            }
        })
        .catch(error => console.error('Error:', error));
}

function setupModalListener() {
    const addModuleBtn = document.getElementById('addModuleBtn');
    const modal = document.getElementById('add-module-modal');
    const closeModal = document.getElementById('close-modal');

    if (addModuleBtn) {
        addModuleBtn.addEventListener('click', function() {
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
    const form = document.getElementById('new-module-form');
    const modal = document.getElementById('add-module-modal');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const nombreModulo = document.getElementById('nombre_modulo').value;
            const estado = document.getElementById('estado').value;

            const addModuleBtn = form.querySelector('button[type="submit"]');
            addModuleBtn.disabled = true; // Deshabilitar el botón

            fetch('add_module.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre_modulo: nombreModulo,
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
                    alert('Módulo agregado con éxito');
                    fetchModules(); 
                } else {
                    alert('Error al agregar módulo: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                modal.style.display = 'none';
                addModuleBtn.disabled = false; // Volver a habilitar el botón
            });
        });
    }
}

function setupEditListeners() {
    const editButtons = document.querySelectorAll('.fa-pencil-square-o');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const moduleId = this.closest('tr').dataset.id;
            editModule(moduleId);
        });
    });
}

function editModule(moduleId) {
    const row = document.querySelector(`tr[data-id="${moduleId}"]`);
    const cells = row.querySelectorAll('td');

    const nombreModulo = cells[0].innerText;
    const estado = cells[1].innerText;

    cells[0].innerHTML = nombreModulo; // No editable
    cells[1].innerHTML = `
        <select class="editable">
            <option value="Activo" ${estado === 'Activo' ? 'selected' : ''}>Activo</option>
            <option value="Inactivo" ${estado === 'Inactivo' ? 'selected' : ''}>Inactivo</option>
        </select>
    `;

    const saveButton = document.createElement('button');
    saveButton.textContent = 'Guardar';
    saveButton.onclick = function() {
        saveModule(moduleId, row);
    };
    saveButton.classList.add('save-button');
    cells[3].innerHTML = '';
    cells[3].appendChild(saveButton);
}

function saveModule(moduleId, row) {
    const inputs = row.querySelectorAll('.editable');
    const estado = inputs[0].value;

    fetch('update_module.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            modulo_id: moduleId,
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
            alert('Módulo actualizado con éxito');
            fetchModules(); 
        } else {
            alert('Error al actualizar módulo: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteModule(moduleId) {
    if (confirm('¿Está seguro de que desea eliminar este módulo?')) {
        fetch('delete_module.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ modulo_id: moduleId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Módulo eliminado con éxito');
                fetchModules(); 
            } else {
                alert('Error al eliminar módulo: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Llamar a fetchModules al cargar la página
document.addEventListener('DOMContentLoaded', fetchModules);
