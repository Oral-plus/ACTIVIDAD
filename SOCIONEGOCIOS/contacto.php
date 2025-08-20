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
        
        th {
            font-size: 13px;
            font-family: "Comfortaa", sans-serif;
            background: #d0efb1;
            padding:  10px;
            font-weight:15px
        
        }

        td {
            font-size: 13px;
            font-family: "Comfortaa", sans-serif;
            padding:  10px;
        }
        
        
    </style>
    
    <?php
// balance.php

if (isset($_GET['cardCode'])) {
    $cardCode = $_GET['cardCode'];

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
SELECT T1.[Name], T1.[FirstName], T1.[LastName], T1.[Address],T1.Cellolar
FROM 
[dbo].[OCRD]  T0 
INNER JOIN OCPR T1 ON T0.[CardCode] = T1.[CardCode]

WHERE T0.CardCode = ?
";

    $params = array($cardCode);
    $stmt = sqlsrv_query($conn, $sql, $params, array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Generar la tabla HTML
    echo "<table border='1'>
            <tr>
                <th>ID de contacto</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Telefono</th>
                 <th>Direccion</th>
                
            </tr>";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tr>
                <td>" . $row['Name'] . "</td>
                <td>" . $row['FirstName'] . "</td>
                <td>" . $row['LastName'] . "</td>
                <td>" . $row['Cellolar'] . "</td>
                 <td>" . $row['Address'] . "</td>
               
              </tr>";
    }
    echo "</table>";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>
</head>
<body>
</body>
</html>