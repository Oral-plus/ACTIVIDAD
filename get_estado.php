<?php
include 'db_connect.php';

header('Content-Type: application/json; charset=UTF-8');

$sql = "
SELECT DISTINCT [dbo].[@ESTADO_LLAMADA].[Code]
FROM OCLG
JOIN [dbo].[@ESTADO_LLAMADA]  ON OCLG.[U_ESTADO_LLAMADA] = [dbo].[@ESTADO_LLAMADA].[Code]
";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(json_encode(['error' => sqlsrv_errors()]));
}

// Mapeo de números a descripciones
$map = [
    '2 (Reprogramar día siguiente)' => 'No contesta en ninguno de los dos teléfonos',
    'CIC (escribir el comentario en la última columna)' => 'Otro Comentario Importante del Cliente',
    'D15 (Reprograma pago)' => 'Dice que paga cuando la factura tenga 15 días',
    'D30' => 'Dice que paga a los 30 días',
    'D8 (Reprogramar pago)' => 'Dice que paga cuando la factura tenga 8 días',
    'P1' => 'Pagos al momento de cada llamada',
    'P1.1' => 'Pago al momento de la llamada de otros clientes a cargo'
];

$Name = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row['Code'] !== null) {
        // Aplicar utf8_encode para asegurar la codificación correcta
        $code = utf8_encode($row['Code']);
        if (isset($map[$code])) {
            // Usar array asociativo para eliminar duplicados
            $Name[$map[$code]] = true;
        }
    }
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Convertir el array asociativo a un array simple para JSON
$Name = array_keys($Name);
echo json_encode($Name, JSON_UNESCAPED_UNICODE);
?>
