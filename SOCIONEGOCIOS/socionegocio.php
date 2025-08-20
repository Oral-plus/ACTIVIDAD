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
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('api.php?action=getCardCodes')
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
                .catch(error => console.error('Error al obtener los CardCodes:', error));
        });

        function fetchCardName() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getCardName', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.cardName) {
                        document.getElementById('cardName').value = response.cardName;
                    } else {
                        console.error('Error al obtener cardName:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchCardFName() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getCardFName', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.CardFName) {
                            document.getElementById('CardFName').value = response.CardFName;
                        } else {
                            console.error('Error al obtener CardFName:', response.error || 'No se encontró CardFName');
                        }
                    } else {
                        console.error('Error en la solicitud:', xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchPhone1() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getPhone1', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.Phone1) {
                        document.getElementById('Phone1').value = response.Phone1;
                    } else {
                        console.error('Error al obtener Phone1:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchPhone2() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getPhone2', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.Phone2) {
                        document.getElementById('Phone2').value = response.Phone2;
                    } else {
                        console.error('Error al obtener Phone2:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchpersona1() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getpersona1', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.persona1) {
                        document.getElementById('persona1').value = response.persona1;
                    } else {
                        console.error('Error al obtener persona1:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchnit() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getnit', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.nit) {
                        document.getElementById('nit').value = response.nit;
                    } else {
                        console.error('Error al obtener nit:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchCelular() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getCelular', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.Celular) {
                        document.getElementById('Celular').value = response.Celular;
                    } else {
                        console.error('Error al obtener Celular:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }



        function fetchEmail() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getEmail', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.Email) {
                        document.getElementById('Email').value = response.Email;
                    } else {
                        console.error('Error al obtener Email:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }


        function fetchNotes() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getNotes', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.Notes) {
                        document.getElementById('Notes').value = response.Notes;
                    } else {
                        console.error('Error al obtener Notes:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchBalance() {
            var cardCode = document.getElementById('cardCode').value;
            if (!cardCode) return;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Balance1.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    document.getElementById('Balance').value = xhr.responseText;
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
//BALANCE
        function openSmallWindow() {
            var cardCode = document.getElementById('cardCode').value;
            var url = 'balance.php?action=getBalance&cardCode=' + encodeURIComponent(cardCode);
            var width = 1057;
            var height = 600;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            window.open(url, 'Consulta de Saldo', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
        }

        //BALANCE
        function openSmallWindows() {
            var cardCode = document.getElementById('cardCode').value;
            var url = 'contacto.php?action=getcontacto&cardCode=' + encodeURIComponent(cardCode);
            var width = 1057;
            var height = 600;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            window.open(url, 'Consulta de Saldo', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
        }
//PESTAÑAS
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

        
        //VENDEDOR
        function fetchvendedor() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'vendedor.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const vendedorInput = document.getElementById('vendedor');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        vendedorInput.value = names[0];
                    } else {
                        vendedorInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
        function fetchnegociador() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'negociador.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const negociadorInput = document.getElementById('negociador');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        negociadorInput.value = names[0];
                    } else {
                        negociadorInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
        function fetchResponsable() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Responsable.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const ResponsableInput = document.getElementById('Responsable');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        ResponsableInput.value = names[0];
                    } else {
                        ResponsableInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchGrupo() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Grupo.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const GrupoInput = document.getElementById('Grupo');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        GrupoInput.value = names[0];
                    } else {
                        GrupoInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchsubgrupo() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'subgrupo.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const subgrupoInput = document.getElementById('subgrupo');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        subgrupoInput.value = names[0];
                    } else {
                        subgrupoInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchCoach() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Coach.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const CoachInput = document.getElementById('Coach');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        CoachInput.value = names[0];
                    } else {
                        CoachInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }

        function fetchcarteradificil() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'carteradificil.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const carteradificilInput = document.getElementById('carteradificil');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        carteradificilInput.value = names[0];
                    } else {
                        carteradificilInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
//condicion de pago
function fetchpago() {
            var cardCode = document.getElementById('cardCode').value;
            if (!cardCode) return;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Pago.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    document.getElementById('GroupNum').value = xhr.responseText;
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }



//condicion de pago
function fetchpago() {
            var cardCode = document.getElementById('cardCode').value;
            if (!cardCode) return;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Pago.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    document.getElementById('GroupNum').value = xhr.responseText;
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
//lista precio
function fetchlista() {
            var cardCode = document.getElementById('cardCode').value;
            if (!cardCode) return;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'lista.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    document.getElementById('lista').value = xhr.responseText;
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }





        
        //RADICACION
        function fetchRadicacion() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Radicacion.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const RadicacionInput = document.getElementById('Radicacion');
                    const names = JSON.parse(xhr.responseText);
                    if (names.length > 0) {
                        RadicacionInput.value = names[0];
                    } else {
                        RadicacionInput.value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
   //DEPARTAMENTO
        function fetchCounty(cardCode) {
            fetch(`County.php?action=getCounty&cardCode=${cardCode}`)
                .then(response => response.json())
                .then(data => {
                    console.log('County recibido:', data);
                    const countyInput = document.getElementById('county');
                    countyInput.value = data.county || '';
                })
                .catch(error => console.error('Error al obtener el County:', error));
        }
           //CIUDAD
           function fetchCity(cardCode) {
            fetch(`City.php?action=getCity&cardCode=${cardCode}`)
                .then(response => response.json())
                .then(data => {
                    console.log('City recibido:', data);
                    const CityInput = document.getElementById('City');
                    CityInput.value = data.City || '';
                })
                .catch(error => console.error('Error al obtener el City:', error));
        }

        //CONDICION PAGO
function fetchcomentario2() {
            var cardCode = document.getElementById('cardCode').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api.php?action=getcomentario2', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.comentario2) {
                        document.getElementById('comentario2').value = response.comentario2;
                    } else {
                        console.error('Error al obtener comentario2:', response.error);
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
        



        //Direcciones
        function fetchdirecciones() {
            const cardCode = document.getElementById('cardCode').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'direcciones.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const addresses = JSON.parse(xhr.responseText);
                    if (addresses.length > 0) {
                        document.getElementById('address').value = addresses[0].Address || '';
                        document.getElementById('Address2').value = addresses[0].Address2 || '';
                        document.getElementById('Address3').value = addresses[0].Address3 || '';
                        document.getElementById('Street').value = addresses[0].Street || '';
                        document.getElementById('Descuento').value = addresses[0].Descuento || '';
                        document.getElementById('credito').value = addresses[0].credito || '';
                    } else {
                        document.getElementById('address').value = '';
                        document.getElementById('Address2').value = '';
                        document.getElementById('Address3').value = '';
                        document.getElementById('Street').value = '';
                        document.getElementById('Descuento').value = '';
                        document.getElementById('credito').value = '';
                    }
                }
            };
            xhr.send('cardCode=' + encodeURIComponent(cardCode));
        }
        
    </script>
</head>
<body>
<header>
    <nav>
        <ul>
        
            <li><a href="../FACTURA/factura1.php"><i class="fas fa-sharp fa-solid fa-users"></i> Factura</a></li>
            <li><a href="../index.php"><i class='fas fa-clipboard-list'></i> Actividad</a></li>
            <li><a href="../QUERY/principal.php"><i class="fas fa-sharp fa-solid fa-users"></i> Query</a></li>
   </ul>
    </nav>
</header>
 

    <center><p>Socio de negocios   <a href="crear.php"><i class='far fa-file-alt' style='font-size:28;color:black'></i></a> </p></center>
    <div class="cuadro">
    <label for="cardCode">CardCode:</label>
    <input type="text" class="card-input" id="cardCode" name="cardCode" list="cardCodeList" oninput="fetchCardName(); fetchPhone1(); fetchPhone2(); fetchCardFName(); fetchBalance(); fetchpersona1(); fetchnit(); fetchNotes();fetchvendedor();fetchnegociador();
    fetchResponsable();fetchGrupo();fetchsubgrupo();fetchCoach();fetchEmail();fetchcarteradificil();fetchcomentario2();fetchRadicacion();fetchdirecciones();fetchCounty();fetchCounty(this.value);fetchCity(this.value);fetchpago();fetchlista();
    fetchlista();fetchCelular();">
    <datalist id="cardCodeList"></datalist>
  
    <label for="Balance" style="margin-left: 550px;" >Balance:
    <a href="#" onclick="openSmallWindow()"><i class="fas fa-solid fa-arrow-right" style="color: #1a7431;"></i></a></label>
    <input type="text" class="card-input" id="Balance" name="Balance" readonly>
    <br>
    <label for="cardName">CardName:</label>
    <input type="text" class="card-input" id="cardName" name="cardName" readonly>

    <label for="Grupo" style="margin-left: 550px;" >Grupo:</label>
    <input type="text" class="card-input" id="Grupo" name="Grupo" readonly>

    <br>
    <label for="CardFName">CardFName:</label>
    <input type="text" class="card-input" id="CardFName" name="CardFName" readonly>

    <label for="subgrupo" style="margin-left: 550px;" >Sub grupo:</label>
    <input type="text" class="card-input" id="subgrupo" name="subgrupo" readonly>
    <br>
    <label for="nit">Nit:</label>
    <input type="text" class="card-input" id="nit" name="nit" readonly>
  

    <div class="tabs" >
    <button class="tablink" onclick="openTab(event, 'General')">General</button>
    <button class="tablink" onclick="openTab(event, 'direcciones')">Direccion</button>
    <button class="tablink" onclick="openTab(event, 'contacto')">Personas de contacto</button>
    <button class="tablink" onclick="openTab(event, 'pago')">Condiciones de pago</button>
</div>

<div id="General" class="tabcontent">
    <br>
    <label for="Celular">Celular:</label>
    <input type="text" class="card-input" id="Celular" name="Celular" readonly>
<br>
<label for="Phone1">Tel 1:</label>
    <input type="text" class="card-input" id="Phone1" name="Phone1" readonly>

    <label for="persona1" style="margin-left: 550px;">Persona:</label>
    <input type="text" class="card-input" id="persona1" name="persona1" readonly>
    <br>
    <label for="Phone2">Tel 2:</label>
    <input type="text" class="card-input" id="Phone2" name="Phone2" readonly>




    <label for="Notes" style="margin-left: 550px;">Comentarios:</label>
    <input type="text" class="card-input" id="Notes" name="Notes" readonly>

    <label for="Email" >Email:</label>
    <input type="text" class="card-input" id="Email" name="Email" readonly>

    <label for="vendedor" style="margin-left: 550px;">Vendedor:</label>
    <input type="text" class="card-input" id="vendedor" name="vendedor" readonly>

    <label for="negociador" >Negociador:</label>
    <input type="text" class="card-input" id="negociador" name="negociador" readonly>
   
    <label for="Responsable" style="margin-left: 550px;" >Responsable:</label>
    <input type="text" class="card-input" id="Responsable" name="Responsable" readonly>
    <br>

    <label for="Coach" >Coach:</label>
    <input type="text" class="card-input" id="Coach" name="Coach" readonly>

    <label for="carteradificil" style="margin-left: 550px;">Cartera dificil cobro:</label>
    <input type="text" class="card-input" id="carteradificil" name="carteradificil" readonly>

    <label for="comentario2">Comentario</label>
    <input type="text" class="card-input" id="comentario2" name="comentario2" readonly>


    <label for="Radicacion" style="margin-left: 550px;">Radicacion factura</label>
    <input type="text" class="card-input" id="Radicacion" name="Radicacion" readonly>
    </div>

<div id="direcciones" class="tabcontent">

  <br>
        <div>
            <label for="address">ID:</label>
            <input type="text" class="card-input" id="address" name="address">

            <label for="county" style="margin-left: 550px;">County:</label>
            <input type="text" id="county" class="card-input" name="county" readonly>
        </div>
        <div>

            <label for="Address2">Address:</label>
            <input type="text" class="card-input" id="Address2" name="Address2">

            <label for="City" style="margin-left: 550px;">City:</label>
            <input type="text" class="card-input" id="City" name="City" readonly>
            
        <label for="Street">Calle:</label>
        <input type="text" class="card-input" id="Street" name="Street" readonly>

        </div>
        <div>
            <label for="Address3">Address:</label>
            <input type="text" class="card-input" id="Address3" name="Address3" readonly>
        </div>
        
       

</div>

<div id="pago" class="tabcontent">
<br>
<label for="GroupNum">Condicion de pago:</label>
            <input type="text" class="card-input" id="GroupNum" name="GroupNum" readonly> 
<br>

<label for="lista">Lista de precio:</label>
<input type="text" class="card-input" id="lista" name="lista" readonly>
<br>
<label for="Descuento">Descuento:</label>
<input type="text" class="card-input" id="Descuento" name="Descuento" readonly>
<br>
<label for="credito">Limite credito:</label>
<input type="text" class="card-input" id="credito" name="credito" readonly>


 </div>


 <div id="contacto" class="tabcontent">

 <label for="Balance" style="margin-left: 550px;" >Personas:
    <a href="#" onclick="openSmallWindows()"><i class="fas fa-solid fa-arrow-right" style="color: #1a7431;"></i></a></label>
   

 </div>


</div>
<script>
    // Abre la primera pestaña por defecto
    document.querySelector('.tablink').click();
</script>

</body>
</html>
