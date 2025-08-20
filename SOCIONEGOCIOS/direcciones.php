<?php
header('Content-Type: application/json');

$tipo = $_POST['cardCode'];

include 'db_connect.php';
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(json_encode(['error' => 'Connection failed: ' . print_r(sqlsrv_errors(), true)]));
}

// Consulta para obtener las direcciones basadas en el CardCode
$sql = "
SELECT CRD1.Address, CRD1.Address2, CRD1.Address3, CRD1.Street, OCRD.Discount, OCRD.CreditLine
FROM CRD1
JOIN OCRD ON OCRD.CardCode = CRD1.CardCode
WHERE OCRD.CardCode = ?
";
$params = array($tipo);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(json_encode(['error' => 'Query failed: ' . print_r(sqlsrv_errors(), true)]));
}

$addresses = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $formattedCreditLine = number_format($row['CreditLine'], 2, ',', '.');
    $addresses[] = [
        'Address' => $row['Address'],
        'Address2' => $row['Address2'],
        'Address3' => $row['Address3'],
        'Street' => $row['Street'],
        'Descuento' => $row['Discount'],
        'credito' => $formattedCreditLine,
    ];
}

sqlsrv_close($conn);

echo json_encode($addresses);
?>
