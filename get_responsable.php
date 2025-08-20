<?php
include 'db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT Code FROM @RESPONSABLE";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(json_encode(['error' => sqlsrv_errors()]));
}

// Mapeo de números a descripciones
$map = [
    1 => 'INGRID',
    2 => 'ROCIO BARAJAS',
    3 => 'YULIETH BARRERA',
    4 => 'PAULA CARDONA',
    5 => 'FLOR LIZZETE',
    6 => 'JOSE GOMEZ',
    7 => 'VALENTINA VARGAS'
];

$Name = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['Code'] !== null && isset($map[$row['Code']])) {
        // Usar array asociativo para eliminar duplicados
        $Name[$map[$row['Code']]] = true;
    }
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Convertir el array asociativo a un array simple para JSON
$Name = array_keys($Name);
echo json_encode($Name);
?>
