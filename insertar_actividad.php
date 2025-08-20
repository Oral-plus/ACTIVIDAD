<?php

// Incluir el archivo de conexión
require_once 'conexion_sap.php';

// Recibir el valor del campo "cardCode" del formulario
$codigo = isset($_POST['cardCode']) ? $_POST['cardCode'] : '';
$asunto = isset($_POST['asunto']) ? $_POST['asunto'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$Comentarios = isset($_POST['Comentarios']) ? $_POST['Comentarios'] : '';
$estadollamada = isset($_POST['estadollamada']) ? $_POST['estadollamada'] : '';
$factura = isset($_POST['factura']) ? $_POST['factura'] : '';
$responsable = isset($_POST['responsable']) ? $_POST['responsable'] : '';
$tllamada = isset($_POST['tllamada']) ? $_POST['tllamada'] : '';
$contenido = isset($_POST['contenido']) ? $_POST['contenido'] : '';

// Verificar que se recibió un código de cliente
if (empty($codigo) || empty($asunto)) {
    echo "Por favor, ingresa un código de cliente.";
    exit;
}

// Mapeo simplificado
$tipoMap = [
    'BACK OFICE' => 4,
    'CARTERA' => 2,
    'GENERAL' => -1,
    'GEOREFERENCIACION' => 3,
    'IFRS' => 1,
    'PRACTICANTE' => 5,
];

$asuntoMap = [
    'GESTION INDEP' => 12,
    'REPROGRAMACION' => 30,
    'AUDITORIA' => 31,
    'BANCARIZACION' => 34,
    'FACTURA PARA RADICAR' => 35,
    'AUDIT. TALONARIO SEM' => 36,
    'LLAMADAS 0-60 DIAS' => 37,
    'VALIDACION DESPACHOS' => 39,
    'GESTION DISTRIBUIDOR' => 41,
    'PEDIDOS BLOQUEADOS' => 43,
    'VALIDACION PEDIDO' => 44,
    'PRIMER DESCUENTO' => 45,
    'SEGUNDO DESCUENTO' => 46,
    'PARETO' => 47,
    'LLAMADAS 60-180 DIAS' => 50,
    'COBRANZA CALL CENTER' => 51,
    'VALIDACION DATOS CN' => 52,
    'MULTIESTRATO' => 53,
    'TIEMPO EXTRA CLIENTE' => 29, // Asumí que deseabas incluir esto también
];

$estadoLlamadaMap = [
    'NO CONTESTA EN NINGUNO DE LOS DOS TELÉFONOS' => '2 (Reprogramar día siguiente)',
    'OTRO COMENTARIO IMPORTANTE DEL CLIENTE' => 'CIC (escribir el comentario en la última columna)',
    'DICE QUE PAGA CUANDO LA FACTURA TENGA 15 DÍAS' => 'D15 (Reprograma pago)',
    'DICE QUE PAGA A LOS 30 DÍAS' => 'D30',
    'DICE QUE PAGA CUANDO LA FACTURA TENGA 8 DÍAS' => 'D8 (Reprogramar pago)',
    'PAGOS AL MOMENTO DE CADA LLAMADA' => 'P1',
    'PAGO AL MOMENTO DE LA LLAMADA DE OTROS CLIENTES A CARGO' => 'P1.1',
];

$responsableMap = [
    'INGRID' => 1,
    'ROCIO BARAJAS' => 2,
    'YULIETH BARRERA' => 3,
    'PAULA CARDONA' => 4,
    'FLOR LIZZETE' => 5,
    'JOSE GOMEZ' => 6,
    'VALENTINA VARGAS' => 7,
];

$tllamadaMap = [
    'RESPONDE' => 1,
    'NO RESPONDE' => 2,
];

$contenidoMap = [
    'CORREO ELECTRONICO' => 1,
    'ESTADO DE CUENTA' => 2,
    'COBRO PRE JURIDICO' => 3,
    'WHATSAPP' => 4,
    'SOLICITUD ACTUALIZACION DE DATOS' => 5,
    'SE DEJA MENSAJE CON LA PERSONA QUE RESPONDE' => 6,
    'SIN GESTION' => 7,
    'VOLVER A LLAMAR' => 8,
    'SE INFORMA AL VENDEDOR PARA PROGRAMAR VISITA DE COBRANZA' => 9,
];

// Asignar valores
$tipo = $tipoMap[strtoupper($tipo)] ?? 0;
$asunto = $asuntoMap[strtoupper($asunto)] ?? 0;
$estadollamada = $estadoLlamadaMap[strtoupper($estadollamada)] ?? '';
$responsable = $responsableMap[strtoupper($responsable)] ?? 0;
$tllamada = $tllamadaMap[strtoupper($tllamada)] ?? 0;
$contenido = $contenidoMap[strtoupper($contenido)] ?? 0;

// Llamar a la función de conexión para obtener el SessionId
$sessionId = conectarSAP();

if ($sessionId) {
    // URL del Service Layer para insertar una nueva actividad
    $url = "https://192.168.2.242:50000/b1s/v1/Activities";

    // Cuerpo de la solicitud POST en formato JSON
    $data = array(
        "odata.etag" => "W/\"356A192B7913B04C54574D18C28D46E6395428AB\"",
        "ActivityCode" => 1,
        "ActivityType" => $tipo,
        "Subject" => $asunto,
        "CardCode" => $codigo,
        "U_ESTADO_LLAMADA" => $estadollamada,
        "Notes" => $Comentarios,
        "U_NUMERO_FACTURA" => $factura,
        "U_Responsable" => $responsable,
        "U_TIPO_LLAMADA" => $tllamada,
        "U_GESTION_LLAMADA" => $contenido,
    );

    $jsonRequestBody = json_encode($data);
    if ($jsonRequestBody === false) {
        echo 'Error al codificar el JSON: ' . json_last_error_msg();
        exit;
    }

    // Mostrar el JSON que se va a enviar (opcional)
    // echo "JSON Enviado: " . $jsonRequestBody . "<br>";

    // Configurar cURL para enviar la solicitud POST
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Cookie: B1SESSION=' . $sessionId,
        'Content-Length: ' . strlen($jsonRequestBody)
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequestBody);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
    } else {
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpStatusCode == 201) {
            echo "Actividad creada exitosamente! Respuesta: \n";
            header("Location: index.php");
            exit();
        } else {
            echo "Error al crear la actividad. Código HTTP: $httpStatusCode\n";
            echo $response;
        }
    }

    curl_close($ch);
} else {
    echo "No se pudo conectar a SAP.";
}
?>
