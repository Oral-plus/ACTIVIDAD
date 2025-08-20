<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        
        .query-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .query-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
            transition: all 0.2s;
            cursor: pointer;
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
        
        .query-btn.secondary {
            background: #f3f4f6;
            color: #374151;
        }
        
        .query-btn.secondary:hover {
            background: #e5e7eb;
        }
        
        .date-form {
            display: none;
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
        }
        
        .date-form.show {
            display: block;
        }
        
        .date-form h3 {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        .date-form p {
            color: #6b7280;
            margin-bottom: 16px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
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
        
        .form-buttons {
            display: flex;
            gap: 8px;
        }
        
        .form-buttons button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-submit {
            background: #3b82f6;
            color: white;
        }
        
        .btn-submit:hover {
            background: #2563eb;
        }
        
        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
        }
        
        .btn-cancel:hover {
            background: #e5e7eb;
        }
        
        .sql-display {
            display: none;
            margin-top: 24px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
        }
        
        .sql-display.show {
            display: block;
        }
        
        .sql-display h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }
        
        .sql-display pre {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            font-size: 12px;
            overflow-x: auto;
            color: #374151;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-database"></i>
                Query Manager
            </h1>
          
        </div>

        <!-- Navigation -->
        <nav class="nav">
            <div class="nav-buttons">
                <a href="../SOCIONEGOCIOS/socionegocio.php" class="nav-btn">
                    <i class="fas fa-users"></i>
                    Socio
                </a>
                <a href="../FACTURA/factura1.php" class="nav-btn">
                    <i class="fas fa-file-text"></i>
                    Factura
                </a>
                <a href="../index.php" class="nav-btn">
                    <i class="fas fa-database"></i>
                    Actividad
                </a>
            </div>
        </nav>

        <!-- Query Cards -->
        <div class="query-grid">
            <div class="query-card">
                <h3>
                    <i class="fas fa-users"></i>
                    Base general de clientes
                </h3>
                <p>Exporta la base completa de clientes con información detallada</p>
                <button class="query-btn secondary" onclick="handleQuery('Consulta1', false)">
                    <i class="fas fa-download"></i>
                    Descargar
                </button>
            </div>

            <div class="query-card">
                <h3>
                    <i class="fas fa-file-text"></i>
                    Actividad V2
                </h3>
                <p>Exporta actividades y llamadas en un rango de fechas</p>
                <button class="query-btn secondary" onclick="handleQuery('Consulta2', true)">
                    <i class="fas fa-download"></i>
                    Configurar
                </button>
            </div>

            <div class="query-card">
                <h3>
                    <i class="fas fa-shopping-cart"></i>
                    Pedidos Manager
                </h3>
                <p>Exporta pedidos del usuario manager con detalles del cliente</p>
                <button class="query-btn primary" onclick="handleQuery('Consulta3', true)">
                    <i class="fas fa-download"></i>
                    Configurar
                </button>
            </div>
        </div>

        <!-- Date Form -->
        <div id="date-form" class="date-form">
            <h3>
                <i class="fas fa-shopping-cart"></i>
                <span id="form-title">Pedidos Manager</span>
            </h3>
            <p>Selecciona el rango de fechas para la consulta</p>
            
            <form id="export-form" action="export.php" method="get">
                <input type="hidden" name="query" id="query-input" value="">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="start_date">Fecha Inicio</label>
                        <input type="date" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Fecha Fin</label>
                        <input type="date" id="end_date" name="end_date" required>
                    </div>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-download"></i>
                        Exportar CSV
                    </button>
                    <button type="button" class="btn-cancel" onclick="hideForm()">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <!-- SQL Display -->
       

    <script>
        function handleQuery(queryName, requiresDates) {
            if (requiresDates) {
                document.getElementById('query-input').value = queryName;
                document.getElementById('date-form').classList.add('show');
                
                // Mostrar SQL solo para Consulta3
                if (queryName === 'Consulta3') {
                    document.getElementById('sql-display').classList.add('show');
                } else {
                    document.getElementById('sql-display').classList.remove('show');
                }
            } else {
                window.location.href = `export.php?query=${queryName}`;
            }
        }

        function hideForm() {
            document.getElementById('date-form').classList.remove('show');
            document.getElementById('sql-display').classList.remove('show');
            document.getElementById('export-form').reset();
        }
    </script>
</body>
</html>