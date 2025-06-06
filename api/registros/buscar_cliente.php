<?php
session_start();

// Datos conexión
$host = "localhost";
$user = "root";
$password = "";
$dbname = "valetparking";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

if (isset($_POST['documento'])) {
    $documento = $conn->real_escape_string($_POST['documento']);

    $sql = "SELECT IdUsuario, Nombres_Usu, Apellidos_Usu, Identificacion_Usu FROM tbcliente WHERE Identificacion_Usu = '$documento' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Guardar IdUsuario en sesión
        $_SESSION['IdUsuario'] = $row['IdUsuario'];

        // Mostrar formulario con datos cargados
        ?>

        <!doctype html>
        <html lang="es">
        <head>
            <title>Datos del Cliente</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="C:\xampp\htdocs\VP 2/css/Estilo_Nuevo_Cliente.css">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        </head>
        <body>
            <!-- Botón hamburguesa para mostrar/ocultar menú -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Menú Lateral -->
            <nav class="sidebar" id="sidebar">
                <div class="menu-title">MENÚ PRINCIPAL</div>
                <div class="menu-grid">
                    <button id="nuevocliente"><i class="fas fa-user-plus"></i> Nuevo Cliente</button>
                    <button id="buscarcliente"><i class="fas fa-search"></i> Buscar Cliente</button>
                    <button id="ubicarvehiculo"><i class="fas fa-car"></i> Ubicar Vehículo</button>
                    <button id="buscarregistro"><i class="fas fa-folder-open"></i> Buscar Registro</button>
                    <button id="historial"><i class="fas fa-history"></i> Historial</button>
                    <button id="informes"><i class="fas fa-chart-bar"></i> Informes</button>
                </div>
                <button id="cierre"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
            </nav>

            <main class="main-content" id="mainContent">
                <div class="formulario">
                    <div class="titulo">DATOS DEL CLIENTE</div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nombres</label>
                            <input type="text" class="form-control" id="nombres" value="<?php echo htmlspecialchars($row['Nombres_Usu']); ?>" disabled readonly>
                        </div>
                        <div class="form-group">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" value="<?php echo htmlspecialchars($row['Apellidos_Usu']); ?>" disabled readonly>
                        </div>
                        <div class="form-group">
                            <label>Documento</label>
                            <input type="text" class="form-control" id="documento" value="<?php echo htmlspecialchars($row['Identificacion_Usu']); ?>" disabled readonly>
                        </div>
                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" class="form-control" id="placa" name="placa">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" id="regresar" onclick="window.history.back()">Regresar</button>
                        <button type="submit" id="guardarPlaca">Guardar Placa</button>
                    </div>
                </div>
            </main>

            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
            <script type="module" src="C:\xampp\htdocs\VP 2/js/index.js"></script>
            <script>
                document.getElementById('menuToggle').addEventListener('click', function () {
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.getElementById('mainContent');
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('active');
                    this.classList.toggle('active');
                });
            </script>
        </body>
        </html>

        <?php
    } else {
        echo "No se encontró cliente con ese documento.";
        echo '<br><a href="buscador_cliente.html">Volver</a>';
    }
} else {
    echo "No se ha enviado ningún documento.";
    echo '<br><a href="buscador_cliente.html">Volver</a>';
}

$conn->close();
?>
