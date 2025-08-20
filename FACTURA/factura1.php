<?php
// Configuración de la conexión a SQL Server
$serverName = "HERCULES";
$connectionOptions = array(
    "Database" => "RBOSKY3",
    "Uid" => "sa",
    "PWD" => "Sky2022*!",
    "CharacterSet" => "UTF-8"
);

function connectToDatabase() {
    global $serverName, $connectionOptions;
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    return $conn;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CardCode'])) {
    $CardCode = filter_input(INPUT_POST, 'CardCode', FILTER_SANITIZE_STRING);

    $conn = connectToDatabase();

    // Consulta SQL para obtener los datos basados en el código
    $sql = "SELECT * FROM CONSULTA_CARTERA2 WHERE CardCode = ? ORDER BY DocDate ASC ";
    $stmt = sqlsrv_query($conn, $sql, array($CardCode));

    if ($stmt === false) {
        echo "Error en la consulta: " . print_r(sqlsrv_errors(), true);
    } elseif (sqlsrv_has_rows($stmt) === false) {
        $message = "Te encuentras a paz y salvo.";
    } else {
        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }
    }
    sqlsrv_close($conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORAL-PLUS</title>
    <link rel="icon" type="image/x-icon" href="imginicio/logobusqueda.jpg">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X2F3Z0336Z"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-X2F3Z0336Z');
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            min-height: 100vh;
            padding: 24px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .header h1 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 16px;
        }
        
        .header p {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .nav {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 16px;
            margin-bottom: 32px;
        }
        
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }
        
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: transparent;
            border: none;
            color: #6b7280;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .nav-btn:hover {
            background: #f3f4f6;
            color: #1f2937;
        }
        
        .query-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
            transition: all 0.2s;
        }
        
        .query-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .query-card h3 {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .query-card p {
            color: #6b7280;
            margin-bottom: 16px;
            line-height: 1.5;
        }
        
        .form-group label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .query-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .query-btn.primary {
            background: #3b82f6;
            color: white;
        }
        
        .query-btn.primary:hover {
            background: #2563eb;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
            margin-top: 24px;
        }
        
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-container th, .table-container td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table-container th {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        
        .table-container td {
            color: #374151;
            font-size: 0.875rem;
        }
        
        .table-container tr:hover {
            background: #f9fafb;
        }
        
        .table-container img {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
            width: 90%;
            max-width: 800px;
            position: relative;
        }
        
        .modal-content h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }
        
        .modal-content .close {
            position: absolute;
            top: 16px;
            right: 16px;
            color: #6b7280;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .modal-content .close:hover {
            color: #1f2937;
        }
        
        .modal-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .modal-table th, .modal-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .modal-table th {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.75rem;
            text-transform: uppercase;
        }
        
        .modal-table td {
            color: #374151;
            font-size: 0.875rem;
        }
        
        .message {
            text-align: center;
            color: #6b7280;
            font-size: 1rem;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-file-invoice"></i>
                Factura deudores
            </h1>
        </div>

        <!-- Navigation -->
        <nav class="nav">
            <div class="nav-buttons">
                <a href="../SOCIONEGOCIOS/socionegocio.php" class="nav-btn">
                    <i class="fas fa-users"></i>
                    Socio
                </a>
                <a href="../index.php" class="nav-btn">
                    <i class="fas fa-database"></i>
                    Actividad
                </a>
                <a href="../QUERY/principal.php" class="nav-btn">
                    <i class="fas fa-clipboard-list"></i>
                    Query
                </a>
            </div>
        </nav>

        <!-- Form -->
        <div class="query-card">
            <h3>
                <i class="fas fa-search"></i>
                Consultar Factura
            </h3>
            <p>Ingrese el código para consultar las facturas deudoras</p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="CardCode">Código</label>
                    <input type="text" name="CardCode" id="CardCode" required>
                </div>
                <button type="submit" class="query-btn primary">
                    <i class="fas fa-search"></i>
                    Consultar
                </button>
            </form>
        </div>

        <!-- Results Table -->
        <?php if (isset($results)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>CÓDIGO</th>
                            <th>NOMBRE</th>
                            <th>FACTURA</th>
                            <th>FECHA VENCIMIENTO</th>
                            <th>VALOR</th>
                            <th>FACTURA</th>
                            <th>DETALLE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['CardCode'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?php echo htmlspecialchars($row['CardName'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?php echo htmlspecialchars($row['DocNum'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?php echo htmlspecialchars($row['DocDueDate'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?php echo htmlspecialchars($row['valor_formateado'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($row['U_HBT_VisorPublico'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGAA4Wd4bco5Xv33GasXrnDdQT5OFXwa3HUQ&s" alt="Ver documento">
                                    </a>
                                </td>
                                <td>
                                    <img src="img/DETALLE.png" class="details-button" data-docnum="<?php echo htmlspecialchars($row['DocNum'], ENT_QUOTES, 'UTF-8') ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Modal -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>
                    <i class="fas fa-info-circle"></i>
                    Detalles del Documento
                </h2>
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th>Pago</th>
                            <th>Factura</th>
                            <th>Detalle</th>
                            <th>Total</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="modal-body">
                        <!-- Aquí se insertarán los detalles del documento con JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal');
            const modalBody = document.getElementById('modal-body');
            const closeButton = document.querySelector('.close');

            document.querySelectorAll('.details-button').forEach(button => {
                button.addEventListener('click', function() {
                    const docNum = this.getAttribute('data-docnum');
                    fetch(`api.php?docNum=${encodeURIComponent(docNum)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (Array.isArray(data) && data.length > 0) {
                                const item = data[0];
                                modalBody.innerHTML = `
                                    <tr>
                                        <td>${item.DocNum || 'No disponible'}</td>
                                        <td>${item.FACTURA || 'No disponible'}</td>
                                        <td>${item.LineMemo || 'No disponible'}</td>
                                        <td>${item.DocTotal || 'No disponible'}</td>
                                        <td>${item.DocDate || 'No disponible'}</td>
                                    </tr>
                                `;
                                modal.style.display = 'flex';
                            } else {
                                modalBody.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron detalles.</td></tr>';
                                modal.style.display = 'flex';
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener detalles:', error);
                            modalBody.innerHTML = '<tr><td colspan="5" class="text-center">Error al cargar detalles.</td></tr>';
                            modal.style.display = 'flex';
                        });
                });
            });

            closeButton.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>