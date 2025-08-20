<?php
header('Content-Type: application/json');

$tipo = $_POST['cardCode'];

include 'db_connect.php';
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(json_encode(['error' => 'Connection failed: ' . print_r(sqlsrv_errors(), true)]));
}

// Consulta para obtener los nombres basados en Tipo
$sql = "
SELECT DISTINCT [@DIFICIL_COBRO].Name
FROM [@DIFICIL_COBRO]
JOIN OCRD ON OCRD.U_CARTERA_DIFICIL_COBRO = [@DIFICIL_COBRO].Code
WHERE OCRD.CardCode = ?
";
$params = array($tipo);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(['error' => 'Query failed: ' . print_r(sqlsrv_errors(), true)]));
}

$names = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $names[] = $row['Name'];
}

sqlsrv_close($conn);

echo json_encode($names);
?>
