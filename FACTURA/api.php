<?php
header('Content-Type: application/json');

// Configuración de la conexión a SQL Server
$serverName = "HERCULES";
$connectionOptions = array(
    "Database" => "RBOKY3",
    "Uid" => "sa",
    "PWD" => "Sky2022*!",
    "CharacterSet" => "UTF-8"
);

// Conectar a SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    echo json_encode(['error' => 'Error de conexión']);
    exit();
}

$docNum = isset($_GET['docNum']) ? $_GET['docNum'] : '';

if ($docNum) {
    // Consulta SQL para obtener los detalles de la factura
    $sql = "SELECT DISTINCT 
    T0.[DocNum], 
    T2.[DocNum] AS FACTURA, 
    T3.[LineMemo], 
    FORMAT(T0.[DocTotal], 'C', 'es-CO') AS DocTotal, -- Formato de moneda en pesos colombianos
    FORMAT(T0.DocDate, 'dd/MM/yyyy') AS DocDate -- Formato de fecha
FROM [dbo].[ORCT] T0 
FULL JOIN RCT2 T1 ON T0.[DocEntry] = T1.[DocNum]
FULL JOIN OINV T2 ON T1.[DocEntry] = T2.[DocEntry]
FULL JOIN dbo.JDT1 T3 ON T3.BaseRef = T0.DocNum 
WHERE T2.[DocNum] = ?
";
    $params = array($docNum);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo json_encode(['error' => 'Error en la consulta']);
    } else {
        $data = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }
        echo json_encode($data);
    }
} else {
    echo json_encode(['error' => 'No se proporcionó docNum']);
}

// Cerrar la conexión a la base de datos
sqlsrv_close($conn);
?>
