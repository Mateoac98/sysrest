function fetchUsers() {
    fetch('list_users.php?ajax=true')
        .then(response => response.text())
        .then(data => {
            const userTable = document.getElementById('user-table');
            if (userTable) {
                userTable.innerHTML = data;  
                setupModalListener();
                setupFormListener(); 
                setupEditListeners();
            } else {
                console.error("Error: No se encontró el elemento con id 'user-table'.");
            }
        })
        .catch(error => console.error('Error:', error));
}

function setupModalListener() {
    const addUserBtn = document.getElementById('addUserBtn');
    const modal = document.getElementById('add-user-modal');
    const closeModal = document.getElementById('close-modal');

    if (addUserBtn) {
        addUserBtn.addEventListener('click', function() {
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
    const form = document.getElementById('new-user-form');
    const modal = document.getElementById('add-user-modal');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const nombreUsuario = document.getElementById('nombre_usuario').value;
            const moduloId = document.getElementById('modulo_id').value;
            const tipoServicio = document.getElementById('tipo_servicio_id').value;
            const password = document.getElementById('password').value; // Asegúrate de obtener la contraseña

            fetch('add_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre_usuario: nombreUsuario,
                    modulo_id: moduloId,
                    tipo_servicio: tipoServicio,
                    password: password // Asegúrate de enviar la contraseña
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
                    alert('Usuario agregado con éxito');
                    fetchUsers(); 
                } else {
                    alert('Error al agregar usuario: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                modal.style.display = 'none'; // Cierra el modal al final
            });
        });
    }
}

function setupEditListeners() {
    const editButtons = document.querySelectorAll('.fa-pencil-square-o');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const usuarioId = this.closest('tr').dataset.id;
            editUser(usuarioId);
        });
    });
}

function setupEditListeners() {
    const editButtons = document.querySelectorAll('.fa-pencil-square-o');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const usuarioId = this.closest('tr').dataset.id;
            editUser(usuarioId);
        });
    });
}

function editUser(usuarioId) {
    const row = document.querySelector(`tr[data-id="${usuarioId}"]`);
    const cells = row.querySelectorAll('td');

    const nombreUsuario = cells[0].innerText;
    const moduloId = cells[1].innerText;
    const tipoServicio = cells[2].innerText;

    cells[0].innerHTML = nombreUsuario; // No editable
    cells[1].innerHTML = moduloId; // No editable
    cells[2].innerHTML = `
        <select class="editable">
            <option value="1" ${tipoServicio === '1' ? 'selected' : ''}>Caja</option>
            <option value="2" ${tipoServicio === '2' ? 'selected' : ''}>Asesoría</option>
        </select>
    `;

    const saveButton = document.createElement('button');
    saveButton.textContent = 'Guardar';
    saveButton.onclick = function() {
        saveUser(usuarioId, row);
    };
    saveButton.classList.add('save-button');
    cells[3].innerHTML = '';
    cells[3].appendChild(saveButton);
}

function saveUser(usuarioId, row) {
    const inputs = row.querySelectorAll('.editable');
    const tipoServicio = inputs[0].value;

    fetch('update_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            usuario_id: usuarioId,
            tipo_servicio: tipoServicio
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
            alert('Usuario actualizado con éxito');
            fetchUsers(); 
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteUser(usuarioId) {
    console.log('ID del usuario a eliminar:', usuarioId); // Agrega esta línea
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        fetch(`delete_user.php?id=${usuarioId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario eliminado con éxito');
                fetchUsers(); 
            } else {
                alert('Error al eliminar usuario: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Inicialización
document.addEventListener("DOMContentLoaded", function() {
    fetchUsers(); 
});
