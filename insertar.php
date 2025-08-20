<?php
// Configuración de conexión
$serverName = "HERCULES"; // Cambia esto por el nombre de tu servidor
$connectionOptions = array(
    "Database" => "RBOSKY3", // Cambia esto por el nombre de tu base de datos
    "Uid" => "sa", // Cambia esto por tu nombre de usuario
    "PWD" => "Sky2022*!" // Cambia esto por tu contraseña
);

// Conectar al servidor
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Verificar la conexión
if ($conn === false) {
    die("Error de conexión: " . print_r(sqlsrv_errors(), true));
}

// Obtener datos del formulario con validación básica
$actividad = isset($_POST['actividad']) ? $_POST['actividad'] : '';
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$cardCode = isset($_POST['cardCode']) ? $_POST['cardCode'] : '';
$asunto = isset($_POST['asunto']) ? $_POST['asunto'] : '';
$cardName = isset($_POST['cardName']) ? $_POST['cardName'] : '';
$phone1 = isset($_POST['phone1']) ? $_POST['phone1'] : '';
$estadollamada = isset($_POST['estadollamada']) ? $_POST['estadollamada'] : '';
$Comentarios = isset($_POST['Comentarios']) ? $_POST['Comentarios'] : '';
$fecha1 = isset($_POST['fecha1']) ? $_POST['fecha1'] : '';
$hora1 = isset($_POST['hora1']) ? $_POST['hora1'] : '';
$fecha2 = isset($_POST['fecha2']) ? $_POST['fecha2'] : '';
$hora2 = isset($_POST['hora2']) ? $_POST['hora2'] : '';
$factura = isset($_POST['factura']) ? $_POST['factura'] : '';
$contenido = isset($_POST['contenido']) ? $_POST['contenido'] : '';
$responsable = isset($_POST['responsable']) ? $_POST['responsable'] : '';
$tllamada = isset($_POST['tllamada']) ? $_POST['tllamada'] : '';
$fechaActual = date('Y-m-d');
$valorStartDate = '10.00.210.11'; // Valor fijo según tu requerimiento

// Validación de campos requeridos
if (empty($actividad) || empty($codigo) || empty($tipo) || empty($cardCode) || empty($asunto)) {
    die("Por favor, complete todos los campos requeridos.");
}

// Mapear la actividad a su código correspondiente
switch (strtoupper($actividad)) {
    case 'LLAMADA': $actividad = 'C'; break;
    case 'REUNION': $actividad = 'M'; break;
    case 'TAREA': $actividad = 'T'; break;
    case 'NOTA': $actividad = 'E'; break;
    case 'CAMPAÑA': $actividad = 'P'; break;
    default: $actividad = 'N'; break;
}

// Mapear el tipo a su código correspondiente
switch (strtoupper($tipo)) {
    case 'BACK OFICE': $tipo = 4; break;
    case 'CARTERA': $tipo = 2; break;
    case 'GENERAL': $tipo = -1; break;
    case 'GEOREFERENCIACION': $tipo = 3; break;
    case 'IFRS': $tipo = 1; break;
    case 'PRACTICANTE': $tipo = 5; break;
    default: $tipo = 0; break;
}

// Mapear el asunto a su código correspondiente
switch (strtoupper($asunto)) {
    case 'TIEMPO EXTRA CLIENTE': $asunto = 29; break;
    case 'REPROGRAMACION': $asunto = 30; break;
    // (Agrega aquí los otros casos)
    case 'VALIDACION DATOS CN': $asunto = 52; break;
    case 'MULTIESTRATO': $asunto = 53; break;
    case 'GESTION INDEP': $asunto = 12; break;
    default: $asunto = 0; break;
}

// Mapear responsable
switch (strtoupper($responsable)) {
    case 'INGRID': $responsable = 1; break;
    case 'ROCIO BARAJAS': $responsable = 2; break;
    case 'MARIA ALEJANDRA OSPINA': $responsable = 3; break;
    case 'PAULA CARDONA': $responsable = 4; break;
    case 'FLOR LIZZETE': $responsable = 5; break;
    case 'JOSE GOMEZ': $responsable = 6; break;
    case 'VALENTINA VARGAS': $responsable = 7; break;
    default: $responsable = 0; break;
}

// Mapear el estado de la llamada
switch (strtoupper($estadollamada)) {
    case '2 (REPROGRAMAR DÍA SIGUIENTE)': $estadollamada = 'No contesta en ninguno de los dos teléfonos'; break;
    case 'CIC (ESCRIBIR EL COMENTARIO EN LA ÚLTIMA COLUMNA)': $estadollamada = 'Otro Comentario Importante del Cliente'; break;
    // (Agrega aquí los otros casos)
    default: $estadollamada = ''; break;
}

// Mapear tipo de llamada
switch (strtoupper($tllamada)) {
    case 'RESPONDE': $tllamada = 1; break;
    case 'NO RESPONDE': $tllamada = 2; break;
    default: $tllamada = 0; break;
}

// Convertir las horas a enteros
function convertTimeToInt($time) {
    $timeParts = explode(':', $time);
    return intval($timeParts[0]) * 100 + intval($timeParts[1]);
}

$hora1Int = !empty($hora1) ? convertTimeToInt($hora1) : 0;
$hora2Int = !empty($hora2) ? convertTimeToInt($hora2) : 0;

// Consulta SQL de inserción
$sql = "INSERT INTO OCLG ([Action], ClgCode, CntctType, CardCode, CntctSbjct, U_ESTADO_LLAMADA, Notes, Recontact, BeginTime, endDate, ENDTime, U_NUMERO_FACTURA, U_Responsable, U_GESTION_LLAMADA, U_TIPO_LLAMADA, CreateDate, VersionNum)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la consulta
$params = array($actividad, $codigo, $tipo, $cardCode, $asunto, $estadollamada, $Comentarios, $fecha1, $hora1Int, $fecha2, $hora2Int, $factura, $responsable, $contenido, $tllamada, $fechaActual, $valorStartDate);

// Iniciar transacción
sqlsrv_begin_transaction($conn);

// Preparar y ejecutar la consulta de inserción
$stmt = sqlsrv_query($conn, $sql, $params);

// Verificar si la inserción fue exitosa
if ($stmt === false) {
    // Si falla, revertir la transacción
    sqlsrv_rollback($conn);
    die("Error en la inserción: " . print_r(sqlsrv_errors(), true));
} else {
    // Si es exitosa, confirmar la transacción
    sqlsrv_commit($conn);
    echo "Datos insertados correctamente.";
}

// Cerrar la conexión
sqlsrv_close($conn);
?>
