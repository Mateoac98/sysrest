document.getElementById('search-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita el envío del formulario

    // Realiza la solicitud AJAX
    fetch('clientes.php?ajax=true')
        .then(response => {
            // Verifica si la respuesta es correcta
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Devuelve el cuerpo de la respuesta como JSON
        })
        .then(data => {
            const clientList = document.getElementById('client-list');
            clientList.innerHTML = ''; // Limpia la lista anterior

            // Recorre los datos y crea elementos para cada cliente
            data.forEach(cliente => {
                const clientItem = document.createElement('div');
                clientItem.className = 'client-item';
                clientItem.innerHTML = `
                    <div class="client-info"><strong>Nombre Completo:</strong> ${cliente.nombre_completo}</div>
                    <div class="client-info"><strong>ID Cliente:</strong> ${cliente.cliente_id}</div>
                    <div class="client-info"><strong>Tipo de Documento:</strong> ${cliente.tipo_documento}</div>
                    <div class="client-info"><strong>Número de Documento:</strong> ${cliente.numero_documento}</div>
                `;
                clientList.appendChild(clientItem);
            });
        })
        .catch(error => console.error('Error:', error));
});
