<?php
include 'db_connect.php';

if (isset($_POST['cardCode'])) {
    $cardCode = $_POST['cardCode'];

    $sql = "SELECT CardName FROM OCRD WHERE CardCode = ?";
    $params = array($cardCode);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo $row['CardName'];
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    echo 'No CardCode provided';
}
?>
