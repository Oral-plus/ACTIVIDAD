<?php
include 'db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT CardCode FROM OCRD";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(json_encode(['error' => sqlsrv_errors()]));
}

$cardCodes = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $cardCodes[] = $row['CardCode'];
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

echo json_encode($cardCodes);
?>
