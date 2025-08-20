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
  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    padding: 15px;
   
    border-radius: 8px;
  }

  .form-grid label {
    display: flex;
    flex-direction: column;
    font-size: 14px;
    font-weight: bold;
    color: #222;
  }

  .form-grid input,
  .form-grid select {
    margin-top: 5px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
   
  }

  .form-grid input:focus,
  .form-grid select:focus {
    outline: none;
    border-color: #4CAF50;
  }

  .form-submit {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .form-submit button {
    background-color: #0a5d1a;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 20px;
    font-weight: bold;
    cursor: pointer;
  }

  .form-submit button:hover {
    background-color: #0e8d29;
  }
</style>
   <style> 
    
    
    
    
    header {
            display: flex;
            justify-content:space-around;
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
        nav a:hover{
            background-color: #abc6d8;
            color: #FFFFFF;
         
        border-radius: 15px;
        }
        
        
        
        .logo img{width: 100px; height: 50px;}
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

        button {
  background-color: #1a7431;
  color: white;
  font-size: 16px;
  font-weight: bold;
  padding: 10px 15px;
  border-radius: 2em;
  cursor: pointer;
  transition: 0.1s ease;
  border-width: 0;
  box-shadow: 1px 5px 0 0 #1a7431;
}

button:hover {
  transform: translateY(-4px);
  box-shadow: 1px 9px 0 0 #1a7431;
}

button:active {
  transform: translateY(4px);
  box-shadow: 0px 0px 0 0 #1a7431;
}
select {
        background: #d0efb1;
        border: 1px solid #d0efb1;
        border-radius: 5px;
       
        width: 200px;
        height: 40px;
        margin-bottom: 10px;
        font-family: "Comfortaa", sans-serif; /* Agregamos un margen inferior para separar los campos */
    }

    .tabs {
        background-color: #f0f9e9; /* Fondo claro */
    display: flex;
    padding: 0;
    border-radius: 15px;
    overflow: hidden;
    font-family: 'Verdana', sans-serif;

        }
        a{
            text-decoration: none;
  color: inherit;
          
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
        } .tablink:hover {
            background-color: #ccc;
            color: #1a7431;
            font-family: 'Alfa Slab One', cursive;
        }

        .tablink.active {
            background-color: #ACECA1;
            font-family: 'Alfa Slab One', cursive;
        }
        .tabcontent {
            display: none;
            padding: 6px 12px;
            font-family: 'Alfa Slab One', cursive;
            border-top: none;
        }
      </style>
   <script>
             // COdigo
             document.addEventListener('DOMContentLoaded', function() {
            fetch('get_cardcode.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Datos recibidos:', data); // Verificar los datos recibidos
                    const datalist = document.getElementById('cardCodeList');
                    datalist.innerHTML = ''; // Limpiar el datalist antes de llenarlo
                    data.forEach(name => {
                        if (name) { // Verificar si el nombre no es vacío o NULL
                            const option = document.createElement('option');
                            option.value = name;
                            datalist.appendChild(option);
                        }
                    });
                })
                .catch(error => console.error('Error al obtener los nombres:', error));
        });

        
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

   
   
  

        
    

window.onload = cargarCampos;

  

    </script>
   
</head>
<body>
<header>
 
     <nav><br>
        <ul>

             <li><a href="socionegocio.php"><i class="fas fa-sharp fa-solid fa-users"></i> Socio</a></li>
             <li><a href="../FACTURA/factura1.php"><i class="fas fa-sharp fa-solid fa-users"></i> Factura</a></li>
            <li><a href="../index.php"><i class='fas fa-clipboard-list'></i> Actividad</a></li>
            <li><a href="../QUERY/principal.php"><i class="fas fa-sharp fa-solid fa-users"></i> Query</a></li>
          </ul>
    </nav>
 

</header>
 
<form action="insertar_socio.php" method="post">
  <center><p>Crear Socio</p></center> 
  <div class="cuadro">

    <label for="cardCode">CardCode:</label>
    <input type="text" class="card-input" id="cardCode" name="cardCode" required>

    <label for="cardType">Tipo*:</label>
    <select id="cardType" name="cardType" required>
      <option value="">Slecion</option>
      <option value="C">Cliente</option>
      <option value="S">Proveedor</option>
      <option value="L">Prospecto</option>
    </select>
    <br>

    <label for="cardName">CardName:</label>
    <input type="text" class="card-input" id="cardName" name="cardName" required>
    <br>

    <label for="cardFName">CardFName:</label>
    <input type="text" class="card-input" id="cardFName" name="cardFName" required>
    <br>

    <label for="codigogrupo">Grupo:</label>
    <select id="codigogrupo" name="codigogrupo" required>
      <option value="108">Empleado</option>
    </select>
    <br>

    <label for="Nit">Nit:</label>
    <input type="text" class="card-input" id="Nit" name="Nit" required>

    <div class="tabs">
      <a href="#" class="tablink" onclick="openTab(event, 'General')">General</a>
      <a href="#" class="tablink" onclick="openTab(event, 'direcciones')">Dirección</a>
      <a href="#" class="tablink" onclick="openTab(event, 'Finanzas')">Finanzas</a>
      <a href="#" class="tablink" onclick="openTab(event, 'Localizacion')">Localizacion</a>
    </div>

    <div id="General" class="tabcontent">
      <br>
      <label for="Phone1">Phone1:</label>
      <input type="text" class="card-input" id="Phone1" name="Phone1" required>

      <label for="Email">Email:</label>
      <input type="email" class="card-input" id="Email" name="Email" required>
    </div>

    <div id="direcciones" class="tabcontent">
      <br>
      <div>
        <label for="ID">ID:</label>
        <input type="text" class="card-input" id="ID" name="ID" required>

        <label for="Calle" style="margin-left: 550px;">Calle:</label>
        <input type="text" id="Calle" class="card-input" name="Calle" required>
      </div>

      <div>
        <label for="City">City:</label>
        <input type="text" class="card-input" id="City" name="City" required>

        <label for="Departamento" style="margin-left: 550px;">Departamento:</label>
        <input type="text" id="Departamento" class="card-input" name="Departamento" required>
      </div>

      <label for="codigoPostal">Codigo postal:</label>
      <input list="listaCodigos" id="codigoPostal" name="codigoPostal" class="card-input" required>
      <datalist id="listaCodigos"></datalist>

      <script>
        fetch('codigos_postales.json')
          .then(response => response.json())
          .then(data => {
            const lista = document.getElementById('listaCodigos');
            data.forEach(item => {
              const option = document.createElement('option');
              option.value = item.codigo;
              option.textContent = `${item.ciudad}`;
              lista.appendChild(option);
            });
          })
          .catch(error => {
            console.error('Error al cargar los códigos postales:', error);
          });
      </script>

      <label for="numerocalle" style="margin-left: 550px;">Numero de la Calle:</label>
      <input type="text" id="numerocalle" class="card-input" name="numerocalle" required>

      <label for="Municipio">Municipio:</label>
      <input list="listaCodigos1" id="Municipio" name="Municipio" class="card-input" required>
      <datalist id="listaCodigos1"></datalist>

      <script>
        fetch('codigos_Municipios.json')
          .then(response => response.json())
          .then(data => {
            const lista = document.getElementById('listaCodigos1');
            data.forEach(item => {
              const option = document.createElement('option');
              option.value = item.ciudad;
              option.textContent = `${item.ciudad}`;
              lista.appendChild(option);
            });
          })
          .catch(error => {
            console.error('Error al cargar los códigos Municipio:', error);
          });
      </script>

      <label for="Direccionmm" style="margin-left: 550px;">Direccion MM:</label>
      <select id="Direccionmm" name="Direccionmm" required>
        <option value="Y">Y</option>
      </select>


      <label for="Estado">Estado:</label>
      <input list="listaCodigos3" id="Estado" name="Estado" class="card-input" >
      <datalist id="listaCodigos3"></datalist>

      <script>
        fetch('codigos_estado.json')
          .then(response => response.json())
          .then(data => {
            const lista = document.getElementById('listaCodigos3');
            data.forEach(item => {
              const option = document.createElement('option');
              option.value = item.codigo;
              option.textContent = `${item.ciudad}`;
              lista.appendChild(option);
            });
          })
          .catch(error => {
            console.error('Error al cargar los códigos postales:', error);
          });
      </script>


    </div>

    <div id="Finanzas" class="tabcontent">
      <br>
      <label for="Cuentasoc">Cuenta asociada:</label>
      <select id="Cuentasoc" name="Cuentasoc" required>
        <option value="2335950113">2335950113</option>
      </select>
    </div>

   

<div id="Localizacion" class="tabcontent">
  <div class="form-grid">
    <label for="Regimen">Regimen tributario:
      <select id="Regimen" name="Regimen" required>
        <option value="RS">Régimen Simplificado</option>
      </select>
    </label>

    <label for="Documento">Tipo Documento:
      <select id="Documento" name="Documento" required>
        <option value="13">Cédula</option>
      </select>
    </label>

    <label for="Entidad">Tipo Entidad:
      <select id="Entidad" name="Entidad" required>
        <option value="1">Natural</option>
      </select>
    </label>

    <label for="economica">Actividad económica:
      <select id="economica" name="economica" required>
        <option value="0010">Asalariado</option>
      </select>
    </label>

    <label for="Magnetico">Medios Magnéticos:
      <input list="listaCodigos2" id="Magnetico" name="Magnetico" class="card-input" required>
      <datalist id="listaCodigos2"></datalist>
    </label>

    <label for="Nomloca">Nombre:
      <input type="text" id="Nomloca" class="card-input" name="Nomloca" required>
    </label>

    <label for="primerapellido">Primer apellido:
      <input type="text" id="primerapellido" class="card-input" name="primerapellido" required>
    </label>

    <label for="segundoapellido">Segundo apellido:
      <input type="text" id="segundoapellido" class="card-input" name="segundoapellido" required>
    </label>

    <label for="Nacionalidad">Nacionalidad:
      <select id="Nacionalidad" name="Nacionalidad" required>
        <option value="1">Nacional</option>
      </select>
    </label>

    <label for="Fiscal">Régimen Fiscal:
      <select id="Fiscal" name="Fiscal" required>
        <option value="">Seleccione</option>
        <option value="04">Régimen Simple</option>
        <option value="05">Régimen Ordinario</option>
        <option value="48">Impuesto sobre las ventas - IVA</option>
        <option value="49">No responsable de IVA</option>
      </select>
    </label>
  </div>

 
</div>

<script>
  fetch('codigos_Magnetico.json')
    .then(response => response.json())
    .then(data => {
      const lista = document.getElementById('listaCodigos2');
      data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.codigo;
        option.textContent = `${item.ciudad}`;
        lista.appendChild(option);
      });
    })
    .catch(error => {
      console.error('Error al cargar los códigos Municipio:', error);
    });
</script>


    <br>
  </div>

  <center>
    <button type="submit">
      Enviar
    </button>
  </center>
</form>

</body>
</html>
