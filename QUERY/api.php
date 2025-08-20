<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="data.csv"'); // Usa .csv para que Excel lo reconozca correctamente
header('Pragma: no-cache');
header('Expires: 0');

$serverName = "HERCULES";
$connectionOptions = array(
    "Database" => "RBOSKY3",
    "Uid" => "sa",
    "PWD" => "Sky2022*!"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die("Error al conectar con la base de datos.");
}

// Obtén el nombre de la consulta desde el parámetro URL
$queryName = isset($_GET['query']) ? $_GET['query'] : '';

// Obtén las fechas desde los parámetros de la URL
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

$sql = '';
switch ($queryName) {
    case 'Consulta1':
        $sql = "SELECT T0.[CardType], 
                       T0.[U_BKV_FECHA_CLIENTE], 
                       T0.[validFor] AS 'Estado',
                       T0.[CardCode],
                       T0.[CardName],
                       (SELECT MAX(T12.[Address]) 
                        FROM CRD1 T12 
                        WHERE T0.CardCode = T12.CardCode) AS DESTINATARIO, 
                       T0.[CardFName],
                       T0.[LicTradNum] AS 'Nit',
                       T10.[Name] AS 'Tipo Exhibidor', 
                       T8.[ListName], 
                       T0.[City],
                       T9.[AgentName],
                       T1.SlpCode,
                       T1.[SlpName] AS 'Vendedor', 
                       T2.[Name] AS 'Mercaderista',
                       T2.U_RUTA AS 'Ruta', 
                       T3.[Name] AS 'Negociador', 
                       T5.[Name] AS 'Coach',
                       T6.[Name] AS 'Transferencista',
                       T7.[Name] AS 'Jefe',
                       T0.[Phone1], 
                       T0.[Phone2],
                       T0.[Fax] AS 'Telefono 3', 
                       T0.[Cellular] AS 'Telefono 4',
                       T0.[E_Mail], 
                       T0.[StreetNo],
                       T0.[Notes],
                       T0.[Discount],
                       T0.[Balance],
                       T0.[U_ACTUALIZACION],
                       MAX(T11.[DocDueDate]) AS 'Fecha Vencimiento', 
                       T4.GroupName
                FROM OCRD T0  
                FULL JOIN OSLP T1 ON T0.[SlpCode] = T1.[SlpCode] 
                LEFT JOIN [dbo].[@MERCADERISTAS] T2 ON T0.[U_MERCADERISTA] = T2.[Code] 
                LEFT JOIN [dbo].[@SKY_NEGOCIADOR] T3 ON T0.[U_NEGOCIADOR] = T3.[Code]
                LEFT JOIN OCRG T4 ON T0.[GroupCode] = T4.[GroupCode]
                FULL JOIN [dbo].[@COACH] T5 ON T0.[U_COACH] = T5.[Code]
                FULL JOIN [dbo].[@TRANSFERENCISTA] T6 ON T0.[U_TRANSFERENCISTA] = T6.[Code]
                FULL JOIN [dbo].[@JEFES_DISTRI] T7 ON T0.[U_JEFES_DISTRIBUIDORES] = T7.[Code]
                INNER JOIN OPLN T8 ON T0.[ListNum] = T8.[ListNum]
                FULL JOIN OAGP T9 ON T0.[AgentCode] = T9.[AgentCode]
                FULL JOIN [dbo].[@EXHIBIDOR] T10 ON T0.[U_TIPO_EXHIBIDOR] = T10.[Code]
                FULL JOIN OINV T11 ON T0.[CardCode] = T11.[CardCode]
                LEFT JOIN [dbo].[@RADICACION] T12 ON T0.[U_radicacion_de_facturas] = T12.[Code]
                GROUP BY T0.[CardType], 
                         T0.[U_BKV_FECHA_CLIENTE], 
                         T0.[validFor],
                         T0.[CardCode],
                         T0.[CardName],
                         T0.[CardFName],
                         T0.[LicTradNum],
                         T10.[Name],
                         T8.[ListName],
                         T0.[City],
                         T9.[AgentName],
                         T1.SlpCode,
                         T1.[SlpName], 
                         T2.[Name],
                         T2.U_RUTA, 
                         T3.[Name], 
                         T5.[Name],
                         T6.[Name],
                         T7.[Name],
                         T0.[Phone1], 
                         T0.[Phone2],
                         T0.[Fax], 
                         T0.[Cellular],
                         T0.[E_Mail], 
                         T0.[StreetNo],
                         T0.[Notes],
                         T0.[Discount],
                         T0.[Balance],
                         T0.[U_ACTUALIZACION],
                         T4.GroupName";
        break;
    case 'Consulta2':
        $sql = "SELECT T0.[CardCode] AS 'CLIENTE', T0.[CardName] AS 'NOMBRE', T0.[Phone1], T0.[Phone2], T0.[Cellular], 
                       T6.[SlpCode] AS 'CODIGO VENDEDOR', T6.[SlpName] AS 'NOMBRE VENDEDOR', T4.[GroupName] AS 'GRUPO', 
                       T8.[Name], T3.[Name] AS 'TIPO', T2.[Name] AS 'ASUNTO',
                       T1.[Recontact] AS 'FECHA INICIO', T1.[endDate] AS 'FECHA FIN', 
                       T1.[BeginTime] AS 'HORA INICIO', T1.[ENDTime] AS 'HORA FIN',
                       T1.[Duration] AS 'DURACION', T1.[U_OPCION] AS 'CONTENIDO2', T1.[Notes] AS 'CONTENIDO', 
                       T1.[Details] AS 'COMENTARIOS', T7.[AgentName] AS 'RESPONSABLE', 
                       T9.Code AS 'Codigo llamada', T9.Name AS 'Descripcion de la llamada',
                       T1.U_NUMERO_FACTURA, T1.[U_Comentario_llamada], T10.Name AS 'Responsable de la llamada', 
                       T11.[Name] AS 'Estado de llamada (Responde-No responde)', T12.Name AS 'Gestion'
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
        break;
    default:
        die("Consulta no encontrada.");
}

// Ejecuta la consulta con los parámetros
$params = array($fechaInicio, $fechaFin);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die("Error en la consulta.");
}

// Abrir la salida de PHP
$output = fopen('php://output', 'w');

// Escribir la fila de encabezado (columnas)
$header = [];
foreach (sqlsrv_field_metadata($stmt) as $field) {
    $header[] = $field['Name'];
}
fputcsv($output, $header, ';'); // Usar punto y coma como delimitador

// Escribir los datos de la consulta en el archivo CSV
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // Formatear campos de fecha (DateTime) antes de escribir en el CSV
    foreach ($row as $key => $value) {
        if ($value instanceof DateTime) {
            $row[$key] = $value->format('Y-m-d H:i:s');
        }
    }
    fputcsv($output, $row, ';'); // Usar punto y coma como delimitador
}

fclose($output);
sqlsrv_close($conn);
?>
