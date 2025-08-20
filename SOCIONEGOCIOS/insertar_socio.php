<?php 


// Incluir el archivo de conexión
require_once 'conexion_sap.php';
// Recibir el valor del campo "cardCode" del formulario
$codigo = isset($_POST['cardCode']) ? $_POST['cardCode'] : '';
$cardType = isset($_POST['cardType']) ? $_POST['cardType'] : '';
$cardName = isset($_POST['cardName']) ? $_POST['cardName'] : '';
$cardFName = isset($_POST['cardFName']) ? $_POST['cardFName'] : '';
$Nit = isset($_POST['Nit']) ? $_POST['Nit'] : '';
$Phone1 = isset($_POST['Phone1']) ? $_POST['Phone1'] : '';
$codigogrupo = isset($_POST['codigogrupo']) ? $_POST['codigogrupo'] : '';
$Email = isset($_POST['Email']) ? $_POST['Email'] : '';


$ID = isset($_POST['ID']) ? $_POST['ID'] : '';
$Calle = isset($_POST['Calle']) ? $_POST['Calle'] : '';   
$City = isset($_POST['City']) ? $_POST['City'] : '';
$Departamento = isset($_POST['Departamento']) ? $_POST['Departamento'] : '';
$Cuentasoc = isset($_POST['Cuentasoc']) ? $_POST['Cuentasoc'] : '';
$codigoPostal = isset($_POST['codigoPostal']) ? $_POST['codigoPostal'] : '';
$numerocalle = isset($_POST['numerocalle']) ? $_POST['numerocalle'] : '';
$Direccionmm = isset($_POST['Direccionmm']) ? $_POST['Direccionmm'] : '';
$Municipio = isset($_POST['Municipio']) ? $_POST['Municipio'] : '';
$Estado = isset($_POST['Estado']) ? $_POST['Estado'] : '';

$Regimen = isset($_POST['Regimen']) ? $_POST['Regimen'] : '';
$Documento = isset($_POST['Documento']) ? $_POST['Documento'] : '';
$Entidad = isset($_POST['Entidad']) ? $_POST['Entidad'] : '';
$economica = isset($_POST['economica']) ? $_POST['economica'] : '';
$Magnetico = isset($_POST['Magnetico']) ? $_POST['Magnetico'] : '';
$Nomloca = isset($_POST['Nomloca']) ? $_POST['Nomloca'] : '';


$primerapellido = isset($_POST['primerapellido']) ? $_POST['primerapellido'] : '';
$segundoapellido = isset($_POST['segundoapellido']) ? $_POST['segundoapellido'] : '';
$Nacionalidad = isset($_POST['Nacionalidad']) ? $_POST['Nacionalidad'] : '';
$Fiscal = isset($_POST['Fiscal']) ? $_POST['Fiscal'] : '';
// Llamar a la función de conexión para obtener el SessionId
$sessionId = conectarSAP();

if ($sessionId) {
    // URL del Service Layer para insertar una nueva actividad
    $url = "https://192.168.2.242:50000/b1s/v1/BusinessPartners";

    // Cuerpo de la solicitud POST en formato JSON
    $data = array(
        "odata.etag" => "W/\"356A192B7913B04C54574D18C28D46E6395428AB\"",
    
        "CardCode" => $codigo,  
        "CardType" => $cardType,  
        "CardName" => $cardName,
        "CardForeignName" => $cardFName,
        "FederalTaxID" => $Nit,
        "Phone1" => $Phone1,
        "GroupCode" => $codigogrupo,
        "EmailAddress" => $Email,
    
        "BilltoDefault" => $ID,
    
        // BPAddresses en vez de campos sueltos
        "BPAddresses" => array(
            array(
                "AddressName" => $ID,
                "AddressType" => "bo_BillTo", // o "bo_ShipTo"
                "Street" => $Calle,
                "StreetNo" => $numerocalle,
                "City" => $City,
                "County" => $Departamento,
                "ZipCode" => $codigoPostal,
                "Country" => "CO",// cambia a tu país si   
                "U_HBT_DirMM" => $Direccionmm,
                "U_HBT_MunMed" => $Municipio,
               
                 
            )
        ),
    
        "DebitorAccount" => $Cuentasoc,
    
        "U_HBT_RegTrib" => $Regimen,
        "U_HBT_TipDoc" => $Documento,
        "U_HBT_TipEnt" => $Entidad,
        "U_HBT_ActEco" => $economica,
        "U_HBT_MunMed" => $Magnetico,
        "U_HBT_Nombres" => $Nomloca,
    "U_HBT_Apellido1" => $primerapellido,
    "U_HBT_Apellido2" => $segundoapellido,
    "U_HBT_Nacional" => $Nacionalidad,
    "U_HBT_RegFis" => $Fiscal,
 "BillToState" => $Estado
                
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
            header("Location: crear.php");
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
