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
    </style>
</head>
<body>
<?php
if (isset($_GET['docNum'])) {
    $docNum = $_GET['docNum'];

    // Configuración de la conexión a la base de datos
    $serverName = "HERCULES";
    $connectionOptions = array(
        "Database" => "RBOSKY3",
        "Uid" => "sa",
        "PWD" => "Sky2022*!"
    );

    // Conexión a la base de datos
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Consulta SQL
    $sql = "
        SELECT DISTINCT 
T0.[DocNum],T2.[DocNum] AS FACTURA, T3.[LineMemo],T0.[DocTotal],T0.DocDate FROM [dbo].[ORCT]  T0 
FULL JOIN RCT2 T1 ON T0.[DocEntry] = T1.[DocNum]
FULL JOIN OINV T2 ON T1.[DocEntry] = T2.[DocEntry]
FULL JOIN dbo.JDT1 T3 ON T3.BaseRef = T0.DocNum 

WHERE T2.[DocNum] = ?
       
    ";

    $params = array($docNum);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Generar la tabla HTML
    echo "<table border='1'>
            <tr>
                <th>Nª Pago</th>
                <th>Nª Factura</th>
                <th>Detalle</th>
                <th>Valor</th>
                <th>Fecha pago</th>
        
            </tr>";
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $docDate = $row['DocDate'] instanceof DateTime ? $row['DocDate']->format('d-m-Y') : $row['DocDate'];
                $docTotalFormatted = number_format($row['DocTotal'], 2, '.', ',');
                echo "<tr>
                        <td>" . htmlspecialchars((string)$row['DocNum']) . "</td>
                        <td>" . htmlspecialchars((string)$row['FACTURA']) . "</td>
                        <td>" . htmlspecialchars((string)$row['LineMemo']) . "</td>
                        <td>" . htmlspecialchars($docTotalFormatted) . "</td>
                        <td>" . htmlspecialchars($docDate) . "</td>
                      </tr>";
            }
            echo "</table>";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>
</body>
</html>
