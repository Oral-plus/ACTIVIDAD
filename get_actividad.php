<?php
include 'db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT DISTINCT Action FROM OCLG";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    echo json_encode(['error' => sqlsrv_errors()]);
    exit;
}

$Action = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    switch ($row['Action']) {
        case 'C':
            $actionValue = 'LLAMADA';
            break;
        case 'M':
            $actionValue = 'REUNION';
            break;
        case 'T':
            $actionValue = 'TAREA';
            break;
        case 'E':
            $actionValue = 'NOTA';
            break;
        case 'P':
            $actionValue = 'CAMPAÑA';
            break;
        case 'N':
            $actionValue = 'OTROS';
            break;
        default:
            $actionValue = $row['Action'];
            break;
    }
    $Action[] = $actionValue;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

echo json_encode($Action);
?>
