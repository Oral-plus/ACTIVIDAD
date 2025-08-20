<?php
header('Content-Type: application/json');

$action = $_GET['action'];

if ($action == 'getCardData') {
    $cardCode = $_POST['cardCode'];
    $docInterno = $_POST['docInterno']; // Asegúrate de obtener también docInterno

    // Conectar a la base de datos (ajusta los detalles según tu configuración)
    $serverName = "HERCULES";
    $connectionOptions = array(
        "Database" => "RBOSKY3",
        "Uid" => "sa",
        "PWD" => "Sky2022*!"
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn === false) {
        die(json_encode(array('error' => sqlsrv_errors())));
    }

    // Consultar datos de CONSULTA_CARTERA
    $sql = "SELECT * FROM CONSULTA_CARTERA2 WHERE CardCode = ? AND DocNum = ?";
    $params = array($cardCode, $docInterno);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(json_encode(array('error' => sqlsrv_errors())));
    }

    $data = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    echo json_encode($data);

    sqlsrv_close($conn);
}
?>
