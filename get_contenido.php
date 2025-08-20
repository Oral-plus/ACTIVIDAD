<?php
include 'db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT U_GESTION_LLAMADA FROM OCLG";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(json_encode(['error' => sqlsrv_errors()]));
}

// Mapeo de números a descripciones
$map = [
    1 => 'CORREO ELECTRONICO',
    2 => 'ESTADO DE CUENTA',
    3 => 'COBRO PRE JURIDICO',
    4 => 'WHATSAPP',
    5 => 'SOLICITUD  ACTUALIZACION DE DATOS',
    6 => 'SE DEJA MENSAJE CON LA PERSONA QUE RESPONDE',
    7 => 'SIN GESTION',
     8 => 'VOLVER A LLAMAR',
      9 => 'SE INFORMA AL VENDEDOR PARA PROGRAMAR VISITA DE COBRANZA'
];

$Name = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['U_GESTION_LLAMADA'] !== null && isset($map[$row['U_GESTION_LLAMADA']])) {
        // Usar array asociativo para eliminar duplicados
        $Name[$map[$row['U_GESTION_LLAMADA']]] = true;
    }
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Convertir el array asociativo a un array simple para JSON
$Name = array_keys($Name);
echo json_encode($Name);
?>
