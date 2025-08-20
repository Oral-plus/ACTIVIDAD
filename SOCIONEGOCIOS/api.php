<?php
include 'db_connect.php';

header('Content-Type: application/json');

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'getCardCodes') {
        // Obtener todos los CardCode
        $sql = "SELECT CardCode FROM OCRD";
        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $cardCodes = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $cardCodes[] = $row['CardCode'];
        }

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
        
        echo json_encode($cardCodes);
    } elseif ($action == 'getCardName' && isset($_POST['cardCode'])) {
        // Obtener CardName basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT CardName FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['cardName' => $row['CardName'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    } elseif ($action == 'getCardFName' && isset($_POST['cardCode'])) {
        // Obtener CardFName basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT CardFName FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['CardFName' => $row['CardFName'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

    } elseif ($action == 'getPhone2' && isset($_POST['cardCode'])) {
        // Obtener Phone2 basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT Phone2 FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['Phone2' => $row['Phone2'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

    } elseif ($action == 'getpersona1' && isset($_POST['cardCode'])) {
        // Obtener CntctPrsn basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT CntctPrsn FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['persona1' => $row['CntctPrsn'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

    } elseif ($action == 'getnit' && isset($_POST['cardCode'])) {
        // Obtener CntctPrsn basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT LictradNum FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['nit' => $row['LictradNum'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

    } elseif ($action == 'getCelular' && isset($_POST['cardCode'])) {
        // Obtener CntctPrsn basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT Cellular FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['Celular' => $row['Cellular'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);


    } elseif ($action == 'getNotes' && isset($_POST['cardCode'])) {
        // Obtener CntctPrsn basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT Notes FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['Notes' => $row['Notes'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);


    } elseif ($action == 'getEmail' && isset($_POST['cardCode'])) {
        // Obtener CntctPrsn basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT E_Mail FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['Email' => $row['E_Mail'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);


    } elseif ($action == 'getcomentario2' && isset($_POST['cardCode'])) {
        // Obtener CntctPrsn basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT FrozenComm FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['comentario2' => $row['FrozenComm'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);



    } elseif ($action == 'getPhone1' && isset($_POST['cardCode'])) {
        // Obtener Phone1 basado en CardCode
        $cardCode = $_POST['cardCode'];

        $sql = "SELECT Phone1 FROM OCRD WHERE CardCode = ?";
        $params = array($cardCode);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(json_encode(['error' => sqlsrv_errors()]));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        echo json_encode(['Phone1' => $row['Phone1'] ?? null]);

        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    echo json_encode(['error' => 'No action specified']);
}
?>
