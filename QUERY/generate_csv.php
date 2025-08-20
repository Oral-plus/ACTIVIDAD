<?php
// generate_csv.php

// Configuración de la base de datos
$serverName = "HERCULES";
$connectionOptions = array(
    "Database" => "RBOSKY3",
    "Uid" => "sa",
    "PWD" => "Sky2022*!"
);

// Conectar a la base de datos
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die("Error al conectar con la base de datos.");
}

// Obtener parámetros de consulta
$queryName = isset($_GET['query']) ? $_GET['query'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Configurar encabezados para la descarga del archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="data.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Inicializar el archivo CSV
$output = fopen('php://output', 'w');

// Definir la consulta SQL y parámetros
$sql = '';
$params = [];

switch ($queryName) {
    case 'Consulta1':
        $sql = "SELECT DISTINCT T19.[CardCode], T19.CardName,T11.SlpCode,T11.SlpName,T7.[BaseRef],
T14.DocNum NroFra,A0.ReconDate,  ISNULL(A1.ReconSum, 0) AS Recaudos,T14.DocTotal 'Valor de la factura',T20.DocNum,T21.ItemCode,T21.[Dscription]
 
 
FROM OITR A0
full JOIN ITR1 AS A1 ON A0.ReconNum = A1.ReconNum
full JOIN OJDT T7 ON A1.[TransId] = T7.[TransId]
LEFT JOIN ORCT T8 ON T7.[TransId] = T8.[TransId]
FULL  JOIN OCRD T10 ON A1.[ShortName] = T10.[CardCode]
 
 
FULL JOIN RCT2 T13 ON T8.[DocEntry] = T13.[DocNum]
left JOIN OINV T14 ON T13.[DocEntry] = T14.[DocEntry]
 
 
 
 
LEFT JOIN OCRD T19 ON T8.CardCode=T19.Cardcode 
LEFT JOIN OSLP T11 ON T19.[SlpCode] = T11.[SlpCode]
LEFT JOIN OCRG T12 ON T19.[GroupCode] = T12.[GroupCode]
LEFT JOIN [@DISTRIBUCION]  T15 ON T19.[U_CANAL_DISTRIBUCION] = T15.[Code]
LEFT JOIN [@COACH]  T16 ON T19.[U_COACH] = T16.[Code]              
LEFT JOIN [@SKY_NEGOCIADOR]  T17 ON T19.[U_NEGOCIADOR] = T17.[Code]
FULL JOIN OAGP  T18 ON T19.AgentCode = T18.[AgentCode]
LEFT JOIN ORIN T20 ON T14.DocNum=T20.U_NUMERO_FACTURA
LEFT JOIN RIN1 T21 ON T20.[DocEntry] = T21.[DocEntry] 

WHERE A0.ReconDate >= [%0] and A0.ReconDate <= [%1] AND T7.[TransType] in('24') AND A0.[ReconType] NOT IN('5')  AND T19.CardCode <> ''
 
 
GROUP BY 
T19.[CardCode], T19.CardName,T11.SlpCode,T11.SlpName,T7.[BaseRef],
T14.DocNum,A0.ReconDate,A1.ReconSum,T14.DocTotal,T20.DocNum,T21.[Dscription],T21.ItemCode";
        break;
    case 'Consulta2':
        if (empty($startDate) || empty($endDate)) {
            die("Por favor, proporciona fechas de inicio y fin.");
        }
        $sql = "SELECT T0.[CardCode]'CLIENTE', 
                       T0.[CardName]'NOMBRE',
                       T0.[Phone1], 
                       T0.[Phone2], 
                       T0.[Cellular],
                       T6.[SlpCode]'CODIGO VENDEDOR', 
                       T6.[SlpName]'NOMBRE VENDEDOR', 
                       T4.[GroupName]'GRUPO',  
                       T8.[Name], 
                       T3.[Name]'TIPO', 
                       T2.[Name]'ASUNTO',
                       T1.[Recontact]'FECHA INICIO', 
                       T1.[endDate]'FECHA FIN', 
                       T1.[BeginTime]'HORA INICIO', 
                       T1.[ENDTime]'HORA FIN',
                       T1.[Duration]'DURACION', 
                       T1.[U_OPCION]'CONTENIDO2', 
                       T1.[Notes]'CONTENIDO', 
                       T1.[Details]'COMENTARIOS',  
                       T7.[AgentName]'RESPONSABLE', 
                       T9.Code 'Codigo llamada',
                       T9.Name 'Descripcion de la llamada',
                       T1.U_NUMERO_FACTURA,
                       T1.[U_Comentario_llamada],
                       T10.Name 'Responsable de la llamada',
                       T11.[Name] 'Estado de llamada (Responde-No responde)',
                       T12.Name 'Gestion'
                FROM [dbo].[OCRD] T0 
                LEFT JOIN [dbo].[OCLG] T1 ON T0.[CardCode] = T1.[CardCode] 
                INNER JOIN [dbo].[OCLS] T2 ON T1.[CntctSbjct] = T2.[Code] 
                INNER JOIN [dbo].[OCLT] T3 ON T1.[CntctType] = T3.[Code] 
                INNER JOIN [dbo].[OCRG] T4 ON T0.[GroupCode] = T4.[GroupCode]
                INNER JOIN [dbo].[OSLP] T6 ON T0.[SlpCode] = T6.[SlpCode] 
                INNER JOIN [dbo].[OAGP] T7 ON T0.[AgentCode] = T7.[AgentCode] 
                INNER JOIN [dbo].[@SKY_NEGOCIADOR] T8 ON T0.[U_NEGOCIADOR] = T8.[Code]
                LEFT JOIN [dbo].[@ESTADO_LLAMADA] T9 ON T1.[U_ESTADO_LLAMADA] = T9.[Code]
                LEFT JOIN [dbo].[@RESPONSABLE] T10 ON T1.[U_Responsable] = T10.[Code]
                LEFT JOIN [dbo].[@LLAMADA] T11 ON T1.[U_TIPO_LLAMADA] = T11.[Code]
                LEFT JOIN [dbo].[@GESTION] T12 ON T1.[U_GESTION_LLAMADA] = T12.[Code]
                WHERE T1.[Recontact] >= ? AND T1.[endDate] <= ?";
        $params = [$startDate, $endDate];
        break;
    case 'Consulta3':
        if (empty($startDate) || empty($endDate)) {
            die("Por favor, proporciona fechas de inicio y fin.");
        }
        $sql = "SELECT DISTINCT T19.[CardCode], T19.CardName, T11.SlpCode, T11.SlpName, T7.[BaseRef],
                        T14.DocNum AS NroFra,A0.ReconDate, ISNULL(A1.ReconSum, 0) AS Recaudos,
                        T14.DocTotal AS 'Valor de la factura', T20.DocNum, T21.ItemCode, T21.[Dscription]
                FROM OITR A0
                FULL JOIN ITR1 AS A1 ON A0.ReconNum = A1.ReconNum
                FULL JOIN OJDT T7 ON A1.[TransId] = T7.[TransId]
                LEFT JOIN ORCT T8 ON T7.[TransId] = T8.[TransId]
                FULL JOIN OCRD T10 ON A1.[ShortName] = T10.[CardCode]
                FULL JOIN RCT2 T13 ON T8.[DocEntry] = T13.[DocNum]
                LEFT JOIN OINV T14 ON T13.[DocEntry] = T14.[DocEntry]
                LEFT JOIN OCRD T19 ON T8.CardCode = T19.CardCode 
                LEFT JOIN OSLP T11 ON T19.[SlpCode] = T11.[SlpCode]
                LEFT JOIN OCRG T12 ON T19.[GroupCode] = T12.[GroupCode]
                LEFT JOIN [@DISTRIBUCION] T15 ON T19.[U_CANAL_DISTRIBUCION] = T15.[Code]
                LEFT JOIN [@COACH] T16 ON T19.[U_COACH] = T16.[Code]              
                LEFT JOIN [@SKY_NEGOCIADOR] T17 ON T19.[U_NEGOCIADOR] = T17.[Code]
                FULL JOIN OAGP T18 ON T19.AgentCode = T18.[AgentCode]
                LEFT JOIN ORIN T20 ON T14.DocNum = T20.U_NUMERO_FACTURA
                LEFT JOIN RIN1 T21 ON T20.[DocEntry] = T21.[DocEntry] 
                WHERE A0.ReconDate >= ? AND A0.ReconDate <= ? 
                  AND T7.[TransType] IN ('24') 
                  AND A0.[ReconType] NOT IN ('5') 
                  AND T19.CardCode <> ''
                GROUP BY T19.[CardCode], T19.CardName, T11.SlpCode, T11.SlpName, T7.[BaseRef],
                         T14.DocNum, A0.ReconDate, A1.ReconSum, T14.DocTotal, T20.DocNum, T21.[Dscription], T21.ItemCode";
        $params = [$startDate, $endDate];
        break;
    default:
        die("Consulta no encontrada.");
}

// Ejecutar la consulta
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die("Error en la consulta.");
}

// Escribir encabezado CSV
$header = [];
foreach (sqlsrv_field_metadata($stmt) as $field) {
    $header[] = $field['Name'];
}
fputcsv($output, $header, ';');

// Escribir filas CSV
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    foreach ($row as $key => $value) {
        if ($value instanceof DateTime) {
            $row[$key] = $value->format('Y-m-d H:i:s');
        }
    }
    fputcsv($output, $row, ';');
}

// Cerrar el archivo y la conexión
fclose($output);
sqlsrv_close($conn);
?>
