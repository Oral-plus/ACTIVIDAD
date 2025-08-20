<?php
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_POST['nameInput'])) {
    echo json_encode(['error' => 'No nameInput provided']);
    exit;
}

$nameInput = $_POST['nameInput'];
error_log("Received nameInput: " . $nameInput); // Depuración

$transformedAction = '';
switch ($nameInput) {
    case 'LLAMADA':
        $transformedAction = 'C';
        break;
    case 'REUNION':
        $transformedAction = 'M';
        break;
    case 'TAREA':
        $transformedAction = 'T';
        break;
    case 'NOTA':
        $transformedAction = 'E';
        break;
    case 'CAMPAÑA':
        $transformedAction = 'P';
        break;
    case 'OTROS':
        $transformedAction = 'N';
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        exit;
}

$sql = "
SELECT OCLS.Name
FROM OCLS
JOIN OCLT ON OCLT.Code = OCLS.Type
WHERE OCLT.Name = ?
";
$params = array($transformedAction);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    error_log("SQL error: " . print_r(sqlsrv_errors(), true)); // Depuración
    echo json_encode(['error' => 'Database query error']);
    exit;
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

if ($row) {
    echo json_encode(['name' => $row['Name']]);
} else {
    echo json_encode(['name' => '']);
}
?>
