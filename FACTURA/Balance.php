<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Factura</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        #modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        #close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        #close-button:hover,
        #close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="modal">
        <div id="modal-content">
            <span id="close-button">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const docNum = urlParams.get('docNum');
            const modal = document.getElementById('modal');
            const modalBody = document.getElementById('modal-body');
            const closeButton = document.getElementById('close-button');

            if (docNum) {
                fetch(`api.php?docNum=${encodeURIComponent(docNum)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data) && data.length > 0) {
                            const item = data[0]; // Asumimos que solo hay un resultado para simplificar
                            modalBody.innerHTML = `
                                <p><strong>Código:</strong> ${item.Pago}</p>
                                <p><strong>Factura:</strong> ${item.FACTURA}</p>
                                <p><strong>Memo:</strong> ${item.LineMemo}</p>
                                <p><strong>Total:</strong> ${item.DocTotal}</p>
                                <p><strong>Fecha:</strong> ${item.DocDate}</p>
                            `;
                            modal.style.display = 'block';
                        } else {
                            modalBody.innerHTML = '<p>No se encontraron detalles.</p>';
                        }
                    })
                    .catch(error => console.error('Error al obtener detalles:', error));
            }

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
