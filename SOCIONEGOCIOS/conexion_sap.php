<?php

function conectarSAP() {
    $url = "http://192.168.2.242:50001/b1s/v1/Login";

    $loginRequest = array(
        "UserName" => "manager",
        "Password" => "SKY0303",
        "CompanyDB" => "RBOSKY3"
    );

    $jsonRequestBody = json_encode($loginRequest);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequestBody);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error de cURL: ' . curl_error($ch);
        return null;
    }

    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpStatusCode == 200) {
        $responseData = json_decode($response, true);
        return $responseData['SessionId'];
    } else {
        echo "Error en la autenticación: HTTP $httpStatusCode\n";
        return null;
    }

    curl_close($ch);
}

?>
