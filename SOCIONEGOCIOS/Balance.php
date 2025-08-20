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
SELECT 
    T0.CardCode, 
    T0.CardName, 
    T4.BaseRef AS 'Doc Interno', 
    T1.BaseRef,
    CASE 
        WHEN T1.[TransType] = 13 THEN 'FR'
        WHEN T1.[TransType] = 24 THEN 'PR'
        WHEN T1.[TransType] = 321 THEN 'ID'
        WHEN T1.[TransType] = 14 THEN 'RC'
        ELSE CAST(T1.[TransType] AS VARCHAR)
    END AS Origen,
    T1.Ref2, 
    T1.RefDate, 
    T1.DueDate,

    -- Importe total bruto
    CASE 
        WHEN Y1.DocTotal IS NULL THEN (T1.Debit - T1.Credit)
        ELSE Y1.DocTotal
    END AS 'importe_total_raw',

    -- Importe total formateado
    FORMAT(
        CASE 
            WHEN Y1.DocTotal IS NULL THEN (T1.Debit - T1.Credit)
            ELSE Y1.DocTotal
        END, 'C', 'es-CO') AS 'importe_total',

    -- Saldo bruto
    SUM(
        CASE
            WHEN T3.DebHab = 'D' THEN (T1.Debit - T1.Credit - T3.ReconSum)
            WHEN T3.DebHab = 'C' THEN (T1.Debit - T1.Credit + T3.ReconSum)
            ELSE (T1.Debit - T1.Credit)
        END
    ) AS 'saldo_raw',

    -- Saldo formateado
    FORMAT(
        SUM(
            CASE
                WHEN T3.DebHab = 'D' THEN (T1.Debit - T1.Credit - T3.ReconSum)
                WHEN T3.DebHab = 'C' THEN (T1.Debit - T1.Credit + T3.ReconSum)
                ELSE (T1.Debit - T1.Credit)
            END
        ), 'C', 'es-CO') AS 'Saldo',

    -- Campos adicionales de importación
    Y1.DocEntry AS 'DocEntry',
    Y1.NumAtCard AS 'NumeroCliente',
    
    -- Para control
    T1.TransId, 
    T1.[TransType]

FROM dbo.OCRD T0
INNER JOIN dbo.JDT1 T1 ON T1.ShortName = T0.CardCode
INNER JOIN dbo.OACT T2 ON T2.AcctCode = T1.Account
INNER JOIN dbo.OJDT T4 ON T4.TransId = T1.TransId
LEFT JOIN dbo.OINV Y1 ON Y1.TransId = T1.TransId
LEFT JOIN dbo.ORIN Y2 ON Y2.TransId = T1.TransId
LEFT JOIN dbo.OSLP Y3 ON Y3.SlpCode = Y1.SlpCode OR Y3.SlpCode = Y2.SlpCode
LEFT JOIN (
    SELECT 
        X0.ShortName AS 'SN', 
        X0.TransId AS 'TransId', 
        SUM(X0.ReconSum) AS 'ReconSum', 
        X0.IsCredit AS 'DebHab', 
        X0.TransRowId AS 'Linea'
    FROM dbo.ITR1 X0
    INNER JOIN dbo.OITR X1 ON X1.ReconNum = X0.ReconNum
    GROUP BY X0.ShortName, X0.TransId, X0.IsCredit, X0.TransRowId
) T3 ON T3.TransId = T1.TransId AND T3.SN = T1.ShortName AND T3.Linea = T1.Line_ID

WHERE 
    T0.CardType = 'C' 
    AND T0.CardCode = ?

GROUP BY 
    Y3.SlpName, 
    T0.CardCode, 
    T0.CardName, 
    T1.TransId, 
    T4.BaseRef, 
    T4.Folionum, 
    T1.RefDate, 
    T1.TaxDate, 
    T1.DueDate, 
    Y1.DocTotal, 
    Y1.DocEntry,
    Y1.NumAtCard,

    T1.Debit, 
    T1.Credit, 
    T3.ReconSum, 
    Y3.SlpCode,
    T1.Ref2,
    T1.BaseRef,
    T1.[TransType]

ORDER BY T1.DueDate DESC
";

    $params = array($cardCode);
    $stmt = sqlsrv_query($conn, $sql, $params, array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Generar la tabla HTML
    echo "<table border='1'>
            <tr>
                <th>CardCode</th>
                <th>CardName</th>
                                <th>Ver Factura</th> 
                <th>Doc Interno</th>
                <th>Origen</th>
                <th>Referencia 2</th>
                <th>RefDate</th>
                <th>DueDate</th>
                <th>Importe Total</th>
                <th>Saldo</th>

            </tr>";
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . $row['CardCode'] . "</td>
                        <td>" . $row['CardName'] . "</td>
                        <td><a href='../FACTURA/factura3.php?cardCode=" . urlencode($row['CardCode']) . "&docInterno=" . urlencode($row['Doc Interno']) . "'>Ver Factura</a></td>
                        <td>" . $row['Doc Interno'] . "</td>
                        <td>" . $row['Origen'] . "</td>
                        <td>" . $row['Ref2'] . "</td>
                        <td>" . $row['RefDate']->format('Y-m-d') . "</td>
                        <td>" . $row['DueDate']->format('Y-m-d') . "</td>
                        <td>" . $row['importe_total'] . "</td>
                        <td>" . $row['Saldo'] . "</td>
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