<?php
include 'db_connect.php';

if (isset($_POST['cardCode'])) {
    $cardCode = $_POST['cardCode'];

    $sql = "SELECT Balance FROM OCRD WHERE CardCode = ?";
    $params = array($cardCode);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo 'Error en la consulta de la base de datos: ' . print_r(sqlsrv_errors(), true);
    } else {
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row) {
            // Usa number_format para formatear el valor en lugar de FORMAT en SQL Server
            echo number_format($row['Balance'], 2, ',', '.');
        } else {
            echo 'No se encontraron resultados.';
        }
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    echo 'Código de tarjeta no proporcionado.';
}
?>
