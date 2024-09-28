function fetchClients() {
    fetch('clientes.php?ajax=true')
        .then(response => response.text())
        .then(data => {
            document.getElementById('client-table').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    // Cargar clientes al inicio
    fetchClients();

    // Abrir el modal al hacer clic en el botón
    document.getElementById('add-client-btn').onclick = function() {
        document.getElementById('add-client-modal').style.display = 'block';
    };

    // Cerrar el modal
    document.getElementById('close-modal').onclick = function() {
        document.getElementById('add-client-modal').style.display = 'none';
    };

    // Agregar nuevo cliente
    document.getElementById('new-client-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const nombreCompleto = document.getElementById('nombre_completo').value;
        const numeroDocumento = document.getElementById('numero_documento').value;
        const tipoDocumento = document.getElementById('tipo_documento').value;

        fetch(`add_client.php?nombre=${encodeURIComponent(nombreCompleto)}&numero_documento=${encodeURIComponent(numeroDocumento)}&tipo_documento=${encodeURIComponent(tipoDocumento)}`, {
            method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            fetchClients(); // Recargar la lista de clientes
            document.getElementById('new-client-form').reset(); // Limpiar el formulario
            document.getElementById('add-client-modal').style.display = 'none'; // Cerrar el modal
        })
        .catch(error => console.error('Error:', error));
    });

    // Cerrar el modal al hacer clic fuera de él
    window.onclick = function(event) {
        if (event.target === document.getElementById('add-client-modal')) {
            document.getElementById('add-client-modal').style.display = 'none';
        }
    };
});
