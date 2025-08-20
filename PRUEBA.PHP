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
if (empty($codigo) || empty($asunto)   ) {
    echo "Por favor, ingresa un código de cliente.";
    exit;
}

switch (strtoupper($tipo)) {
    case 'BACK OFICE': $tipo = 4; break;
    case 'CARTERA': $tipo = 2; break;
    case 'GENERAL': $tipo = -1; break;
    case 'GEOREFERENCIACION': $tipo = 3; break;
    case 'IFRS': $tipo = 1; break;
    case 'PRACTICANTE': $tipo = 5; break;
    default: $tipo = 0; break;
}



switch (strtoupper($asunto)) {
    case 'TIEMPO EXTRA CLIENTE': $asunto = 29; break;
    case 'REPROGRAMACION': $asunto = 30; break;
    // (Agrega aquí los otros casos)
    case 'VALIDACION DATOS CN': $asunto = 52; break;
    case 'MULTIESTRATO': $asunto = 53; break;
    case 'GESTION INDEP': $asunto = 12; break;
    default: $asunto = 0; break;
}



switch (strtoupper($estadollamada)) {
    case 'No contesta en ninguno de los dos teléfonos': $estadollamada = '2 (Reprogramar día siguiente)'; break;
    case 'Otro Comentario Importante del Cliente': $estadollamada = 'CIC (escribir el comentario en la última columna)'; break;
    case 'Dice que paga cuando la factura tenga 15 días': $estadollamada = 'D15 (Reprograma pago)'; break;
    case 'Dice que paga a los 30 días': $estadollamada = 'D30'; break;
    case 'Dice que paga cuando la factura tenga 8 días': $estadollamada = 'D8 (Reprogramar pago)'; break;
    case 'Pagos al momento de cada llamada': $estadollamada = 'P1'; break;
    case 'Pago al momento de la llamada de otros clientes a cargo': $estadollamada = 'P1.1'; break;
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
// Mapear tipo de llamada
switch (strtoupper($tllamada)) {
    case 'RESPONDE': $tllamada = 1; break;
    case 'NO RESPONDE': $tllamada = 2; break;
    default: $tllamada = 0; break;
}

switch (strtoupper($contenido)) {
    case 'CORREO ELECTRONICO': $contenido = 1; break;
    case 'ESTADO DE CUENTA': $contenido = 2; break;
    case 'COBRO PRE JURIDICO': $contenido = 3; break;
    case 'WHATSAPP': $contenido = 4; break;
    case 'SOLICITUD  ACTUALIZACION DE DATOS': $contenido = 5; break;
    case 'SE DEJA MENSAJE CON LA PERSONA QUE RESPONDE': $contenido = 6; break;
    case 'SIN GESTION': $contenido = 7; break;
    case 'VOLVER A LLAMAR': $contenido = 8; break;
    case 'SE INFORMA AL VENDEDOR PARA PROGRAMAR VISITA DE COBRANZA': $contenido = 9; break;
   
}

// Llamar a la función de conexión para obtener el SessionId
$sessionId = conectarSAP();

if ($sessionId) {
    // URL del Service Layer para insertar una nueva actividad
    $url = "https://192.168.2.242:50000/b1s/v1/Activities";

    // Cuerpo de la solicitud POST en formato JSON
    $data = array(
        "odata.etag" => "W/\"356A192B7913B04C54574D18C28D46E6395428AB\"",
        "ActivityCode" => 1,
        "ActivityType" => $tipo, //TIPO
        "Subject" => $asunto, // asunto
        "CardCode" => $codigo,  // CODIGO CLIENTE
        "U_ESTADO_LLAMADA" => $estadollamada, //ESTADO LLAMADA
        "Notes" => $Comentarios, //COMETARIOS
        "U_NUMERO_FACTURA" => $factura, //COMETARIOS
        "U_Responsable" => $responsable, //COMETARIOS
  
     "U_TIPO_LLAMADA" => $tllamada, //COMETARIOS
     "U_GESTION_LLAMADA" => $contenido //COMETARIOS
     
    );

    $jsonRequestBody = json_encode($data);

    // Verificar si la codificación a JSON fue exitosa
    if ($jsonRequestBody === false) {
        echo 'Error al codificar el JSON: ' . json_last_error_msg();
        exit;
    }

    // Mostrar el JSON que se va a enviar
    echo "JSON Enviado: " . $jsonRequestBody . "<br>";

    // Configurar cURL para enviar la solicitud POST
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Cookie: B1SESSION=' . $sessionId, // Usa el SessionId para autenticar
        'Content-Length: ' . strlen($jsonRequestBody)
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequestBody);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Deshabilitar verificación SSL para pruebas

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar si hubo un error en cURL
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
    } else {
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

       
        if ($httpStatusCode == 201) {
            echo "Actividad creada exitosamente! Respuesta: \n";
            header("Location: index.php");
            exit(); // Asegúrate de salir después de redirigir
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
