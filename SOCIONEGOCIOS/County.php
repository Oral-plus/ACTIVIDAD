<?php
$serverName = "HERCULES";
$connectionOptions = [
    "Database" => "RBOSKY3",
    "Uid" => "sa",
    "PWD" => "Sky2022*!",
    "CharacterSet" => "UTF-8"
];

// Establecer conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die(json_encode(['error' => sqlsrv_errors()], JSON_UNESCAPED_UNICODE));
}

if ($_GET['action'] == 'getCardCodes') {
    $sql = "SELECT CardCode FROM OCRD";
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        die(json_encode(['error' => sqlsrv_errors()], JSON_UNESCAPED_UNICODE));
    }

    $cardCodes = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $cardCodes[] = $row['CardCode'];
    }

    echo json_encode($cardCodes, JSON_UNESCAPED_UNICODE);
}

if ($_GET['action'] == 'getCounty' && isset($_GET['cardCode'])) {
    $cardCode = $_GET['cardCode'];
    $sql = "SELECT County FROM CRD1 WHERE CardCode = ?";
    $params = [$cardCode];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(json_encode(['error' => sqlsrv_errors()], JSON_UNESCAPED_UNICODE));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $county = $row ? $row['County'] : null;

    echo json_encode(['county' => $county], JSON_UNESCAPED_UNICODE);
}

sqlsrv_close($conn);
?>
