<?php
include 'db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT U_TIPO_LLAMADA FROM OCLG";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(json_encode(['error' => sqlsrv_errors()]));
}

// Mapeo de números a descripciones
$map = [
    1 => 'RESPONDE',
    2 => 'NO RESPONDE',
 
];

$Name = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['U_TIPO_LLAMADA'] !== null && isset($map[$row['U_TIPO_LLAMADA']])) {
        // Usar array asociativo para eliminar duplicados
        $Name[$map[$row['U_TIPO_LLAMADA']]] = true;
    }
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Convertir el array asociativo a un array simple para JSON
$Name = array_keys($Name);
echo json_encode($Name);
?>




