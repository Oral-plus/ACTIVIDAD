<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar CardCode</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@500..700&display=swap" rel="stylesheet">
    <style>
        /* Tus estilos aquí */
    </style>
</head>
<body>
<header>
    <nav>
        <ul>
            <!-- Enlaces de navegación -->
        </ul>
    </nav>
</header>

<center><p>Socio de negocios</p></center> 
<div class="cuadro">
    <label for="cardCode">CardCode:</label>
    <input type="text" class="card-input" id="cardCode" name="cardCode" list="cardCodeList" oninput="fetchContactData();">
    <datalist id="cardCodeList"></datalist>

    <div class="tabs">
        <button class="tablink" onclick="openTab(event, 'contacto')">Personas de contacto</button>
    </div>

    <div id="contacto" class="tabcontent">
        <!-- La tabla se insertará aquí mediante JavaScript -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('api.php?action=getCardCodes')
            .then(response => response.text())  // Obtén la respuesta como texto
            .then(text => {
                try {
                    const data = JSON.parse(text);  // Intenta parsear el texto como JSON
                    console.log('Datos recibidos:', data);
                    const datalist = document.getElementById('cardCodeList');
                    datalist.innerHTML = '';
                    data.forEach(name => {
                        if (name) {
                            const option = document.createElement('option');
                            option.value = name;
                            datalist.appendChild(option);
                        }
                    });
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                }
            })
            .catch(error => console.error('Error al obtener los CardCodes:', error));
    });

    // Función para cambiar de pestaña
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    function fetchContactData() {
        var cardCode = document.getElementById('cardCode').value;
        fetch('contacto.php?cardCode=' + encodeURIComponent(cardCode))
            .then(response => response.text())  // Obtén la respuesta como texto
            .then(text => {
                try {
                    const data = JSON.parse(text);  // Intenta parsear el texto como JSON
                    if (Array.isArray(data)) {
                        const contactoDiv = document.getElementById('contacto');
                        let tableHtml = '<table border="1">' +
                            '<tr>' +
                            '<th>Nombre</th>' +
                            '<th>Primer Nombre</th>' +
                            '<th>Apellido</th>' +
                            '<th>Telefono</th>' +
                            '<th>Dirección</th>' +
                            '</tr>';

                        data.forEach(row => {
                            tableHtml += '<tr>' +
                                '<td>' + (row.Name || '') + '</td>' +
                                '<td>' + (row.FirstName || '') + '</td>' +
                                '<td>' + (row.LastName || '') + '</td>' +
                                '<td>' + (row.Cellolar || '') + '</td>' +
                                '<td>' + (row.Address || '') + '</td>' +
                                '</tr>';
                        });

                        tableHtml += '</table>';
                        contactoDiv.innerHTML = tableHtml;
                    } else {
                        console.error('Datos recibidos no son un arreglo:', data);
                    }
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                }
            })
            .catch(error => console.error('Error al obtener los datos de contacto:', error));
    }

    // Inicializar la primera pestaña
    document.querySelector('.tablink').click();
</script>
</body>
</html>
