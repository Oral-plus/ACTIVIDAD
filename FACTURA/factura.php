<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar CardCode</title>
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@500..700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
       header {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        
        nav {
            text-align: left; 
            font-size: 15px;
        }
        
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        nav li {
            display: inline;
            margin-right: 20px; /* Espacio entre los enlaces */
            font-family: "Comfortaa", sans-serif;
        }
        
        nav a {
            text-decoration: none;
            color: #000000;
            padding: 5px;
        }
        
        nav a:hover {
            background-color: #abc6d8;
            color: #FFFFFF;
            border-radius: 15px;
        }
        
        .logo img { width: 100px; height: 50px; }
        .buttoncerrar {
            -moz-appearance: none;
            -webkit-appearance: none;
            appearance: none;
            border: none;
            background: none;
            color: #0f1923;
            cursor: pointer;
            position: relative;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 14px;
            transition: all .15s ease;
        }

        /* Estilos para las pestañas */
        .tabs {
            overflow: hidden;
            background-color: #e9f5db;
            margin-top: 20px;
            border-radius: 10px;
            color: #1a7431;
            font-family: 'Alfa Slab One', cursive;
        }

        .tablink {
            background-color: inherit;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            color: #1a7431;
            font-family: 'Alfa Slab One', cursive;
        }

        .tablink:hover {
            background-color: #ccc;
            color: #1a7431;
            font-family: 'Alfa Slab One', cursive;
        }

        .tablink.active {
            background-color: #ACECA1;
            font-family: 'Alfa Slab One', cursive;
        }

        /* Estilos para el contenido de las pestañas */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            font-family: 'Alfa Slab One', cursive;
            border-top: none;
        }

        th {
            font-size: 13px;
            font-family: "Comfortaa", sans-serif;
            background: #ffc4d6;
            padding: 10px;
            font-weight: 15px;
        }

        td {
            font-size: 13px;
            font-family: "Comfortaa", sans-serif;
            padding: 10px;
        }

        table{   
             WIDTH: 100%;
             text-align: center;
        }

        img{   
             WIDTH: 30px;
             text-align: center;
        }

      
    </style>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../SOCIONEGOCIOS/socionegocio.php"><i class="fas fa-sharp fa-solid fa-users"></i> Socio</a></li>
            <li><a href="../index.php"><i class='fas fa-clipboard-list'></i> Actividad</a></li>
        </ul>
    </nav>
</header>

<center><p>Factura deudores</p></center> 
<div class="cuadro">
    <label for="cardCode">CardCode:</label>
    <input type="text" class="card-input" id="cardCode" name="cardCode" list="cardCodeList" value="<?php echo isset($_GET['cardCode']) ? htmlspecialchars($_GET['cardCode']) : ''; ?>">
    <datalist id="cardCodeList"></datalist>

    <label for="docInterno">Doc Interno:</label>
    <input type="text" class="card-input" id="docInterno" name="docInterno" value="<?php echo isset($_GET['docInterno']) ? htmlspecialchars($_GET['docInterno']) : ''; ?>">

    <button id="consultarBtn" class="tablink">Consultar</button>
</div>

<br><br>
<div id="dataContainer"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cardCodeInput = document.getElementById('cardCode');
        const docInternoInput = document.getElementById('docInterno');
        const dataContainer = document.getElementById('dataContainer');
        const consultarBtn = document.getElementById('consultarBtn');

        if (!cardCodeInput || !dataContainer || !consultarBtn || !docInternoInput) {
            console.error('Elementos no encontrados en el DOM');
            return;
        }

        // Obtener datos para el datalist
        fetch('api2.php?action=getCardCode')
            .then(response => response.json())
            .then(data => {
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
            })
            .catch(error => console.error('Error al obtener los CardCode:', error));

        function fetchCardData() {
            var cardCode = cardCodeInput.value;
            var docInterno = docInternoInput.value;

            if (cardCode.trim() === '' || docInterno.trim() === '') {
                return;
            }
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api2.php?action=getCardData', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (Array.isArray(response)) {
                        displayCardData(response);
                    } else {
                        console.error('Error al obtener datos de CONSULTA_CARTERA:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode) + '&docInterno=' + encodeURIComponent(docInterno));
        }

        function displayCardData(data) {
            dataContainer.innerHTML = '';

            if (data.length === 0) {
                dataContainer.innerHTML = '<p>No se encontraron datos.</p>';
            } else {
                let tableHTML = `
                    <table>
                        <thead>
                            <tr>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>FACTURA</th>
                                <th>FECHA VENCIMIENTO</th>
                                <th>VALOR</th>
                                <th>FACTURA</th>
                                <th>DETALLE</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.forEach(item => {
                    tableHTML += `
                        <tr>
                            <td>${item.CardCode}</td>
                            <td>${item.CardName}</td>
                            <td>${item.DocNum}</td>
                            <td>${item.DocDueDate}</td>
                            <td>${item.valor_formateado}</td>
                            <td>
                                <a href="${item.U_HBT_VisorPublico}" target="_blank">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGAA4Wd4bco5Xv33GasXrnDdQT5OFXwa3HUQ&s" alt="Ver documento" width="30px">
                                </a>
                            </td>
                            <td>
                                <img src='img/DETALLE.png' style="cursor: pointer;" class="details-button" data-docnum="${item.DocNum}">
                            </td>
                        </tr>
                    `;
                });

                tableHTML += `
                        </tbody>
                    </table>
                `;

                dataContainer.innerHTML = tableHTML;

                // Añadir event listeners para los botones de detalles
                const detailButtons = document.querySelectorAll('.details-button');
                detailButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const docNum = this.getAttribute('data-docnum');
                        openDetailsPage(docNum);
                    });
                });
            }
        }

        function openDetailsPage(docNum) {
            const url = `balance.php?docNum=${encodeURIComponent(docNum)}`;
            window.open(url, '_blank', 'width=1057,height=600');
        }

        cardCodeInput.addEventListener('input', fetchCardData);
        docInternoInput.addEventListener('input', fetchCardData);
        consultarBtn.addEventListener('click', fetchCardData);
    });
</script>
</body>
</html>
