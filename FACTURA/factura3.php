<?php
// Configuración de la conexión a SQL Server
$serverName = "HERCULES";
$connectionOptions = array(
    "Database" => "RBOSKY3",
    "Uid" => "sa",
    "PWD" => "Sky2022*!",
    "CharacterSet" => "UTF-8"
);

function connectToDatabase() {
    global $serverName, $connectionOptions;
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    return $conn;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CardCode']) && isset($_POST['docInterno'])) {
    $CardCode = filter_input(INPUT_POST, 'CardCode', FILTER_SANITIZE_STRING);
    $DocNum = filter_input(INPUT_POST, 'docInterno', FILTER_SANITIZE_STRING);

    $conn = connectToDatabase();

    // Consulta SQL para obtener los datos basados en el código
    $sql = "SELECT * FROM CONSULTA_CARTERA2 WHERE CardCode = ? AND DocNum = ?";
    $stmt = sqlsrv_query($conn, $sql, array($CardCode, $DocNum));

    if ($stmt === false) {
        echo "Error en la consulta: " . print_r(sqlsrv_errors(), true);
    } elseif (sqlsrv_has_rows($stmt) === false) {
        $message = "Te encuentras a paz y salvo.";
    } else {
        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }
    }
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="index1.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@500..700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORAL-PLUS</title>
    <link rel="stylesheet" type="text/css" href="registro.css">
    <link rel="icon" type="image/x-icon" href="imginicio/logobusqueda.jpg">

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X2F3Z0336Z"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-X2F3Z0336Z');
    </script>

    <style>
        body {
            background-color: #ffffff;
            font-family: Arial, sans-serif;
            width: 100%;
        }

        .form-container {
  
            flex-direction: column;
            align-items: center;
        }

        .form-container label {
            margin-bottom: 10px;
        }

     

        .form-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            text-align: center;
        }

        th, td {
            font-size: 13px;
            font-family: "Comfortaa", sans-serif;
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background: #ffc4d6;
            font-weight: bold;
        }

        #portal {
            display: none;
        }

        @media screen and (max-width: 800px) {
            #portal {
                display: block;
            }
        }

        /* Modal Styles */
        #modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        #modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styles for modal table */
        .modal-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modal-table th, .modal-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .modal-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <center><p>Factura deudores</p></center> 
        <br>
        <div class="form-container">
            <label for="CardCode">Ingrese el código:</label>
            <input type="text" name="CardCode" class="card-input" id="CardCode" required value="<?php echo isset($_GET['cardCode']) ? htmlspecialchars($_GET['cardCode']) : ''; ?>">
          
            <label for="docInterno">Doc Interno:</label>
            <input type="text" class="card-input" id="docInterno" name="docInterno" value="<?php echo isset($_GET['docInterno']) ? htmlspecialchars($_GET['docInterno']) : ''; ?>">
          
            <button type="submit">Consultar</button>
        </div>
        <br><br><br>
    </form>

    <?php if (isset($results)): ?>
        <table class="table">
            <thead class="table-success table-striped">
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
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['CardCode'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?php echo htmlspecialchars($row['CardName'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?php echo htmlspecialchars($row['DocNum'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?php echo htmlspecialchars($row['DocDueDate'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?php echo htmlspecialchars($row['valor_formateado'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['U_HBT_VisorPublico'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGAA4Wd4bco5Xv33GasXrnDdQT5OFXwa3HUQ&s" alt="Ver documento" width="30px">
                            </a>
                        </td>
                        <td>
                            <img src='img/DETALLE.png' style="cursor: pointer;" width="30px" class="details-button" data-docnum="<?php echo htmlspecialchars($row['DocNum'], ENT_QUOTES, 'UTF-8') ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Modal -->
    <div id="modal">
        <div id="modal-content">
            <span class="close">&times;</span>
            <h2>Detalles del Documento</h2>
            <table class="modal-table">
                <thead>
                    <tr>
                        <th>Pago</th>
                        <th>Factura</th>
                        <th>Detalle</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody id="modal-body">
                    <!-- Aquí se insertarán los detalles del documento con JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');
            const modalBody = document.getElementById('modal-body');
            const closeButton = document.querySelector('.close');

            document.querySelectorAll('.details-button').forEach(button => {
                button.addEventListener('click', function() {
                    const docNum = this.getAttribute('data-docnum');
                    fetch(`api.php?docNum=${encodeURIComponent(docNum)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (Array.isArray(data) && data.length > 0) {
                                const item = data[0]; // Asumimos que solo hay un resultado para simplificar
                                modalBody.innerHTML = `
                                    <tr>
                                        <td>${item.DocNum || 'No disponible'}</td>
                                        <td>${item.FACTURA || 'No disponible'}</td>
                                        <td>${item.LineMemo || 'No disponible'}</td>
                                        <td>${item.DocTotal || 'No disponible'}</td>
                                        <td>${item.DocDate || 'No disponible'}</td>
                                    </tr>
                                `;
                                modal.style.display = 'block';
                            } else {
                                modalBody.innerHTML = '<tr><td colspan="5">No se encontraron detalles.</td></tr>';
                                modal.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener detalles:', error);
                            modalBody.innerHTML = '<tr><td colspan="5">Error al cargar detalles.</td></tr>';
                            modal.style.display = 'block';
                        });
                });
            });

            closeButton.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
