<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'valetparking';
$user = 'root';
$pass = ''; // Cambia si tu contraseña es diferente
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Iniciar sesión
session_start();

// Obtener ID del cliente desde la sesión
$idCliente = $_SESSION['IdUsuario'] ?? null;

if (!$idCliente) {
    die("No se encontró el ID del cliente en sesión.");
}

// Obtener datos del formulario
$placa = $_POST['Placa'] ?? '';
$ubicacion = $_POST['ubicacion'] ?? '';
$elementos = $_POST['elementos'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';
$imagen_base64 = $_POST['imagen_canvas'] ?? '';

if (empty($placa)) {
    die("La placa es obligatoria.");
}

// Guardar imagen en el servidor
$directorio = '../../src/sources/img_novedades/';
if (!file_exists($directorio)) {
    mkdir($directorio, 0777, true);
}

$nombreArchivo = 'novedad_' . time() . '.jpg';
$rutaArchivo = $directorio . $nombreArchivo;
$rutaParaDB = '../src/sources/img_novedades/' . $nombreArchivo;

$imagen_base64 = str_replace('data:image/jpeg;base64,', '', $imagen_base64);
$imagen_base64 = str_replace(' ', '+', $imagen_base64);
$imagen_binaria = base64_decode($imagen_base64);

if ($imagen_binaria === false || !file_put_contents($rutaArchivo, $imagen_binaria)) {
    die("No se pudo guardar la imagen.");
}

// Insertar en la tabla tb_registro
$fechaHora = date('Y-m-d H:i:s');
$stmtInsert = $pdo->prepare("INSERT INTO tb_registro (Placa, imagen_novedad, Ubicacion_veh, observaciones, Objet_Valor, Entrada, Id_Cliente_FK)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmtInsert->execute([
    $placa,
    $rutaParaDB,
    $ubicacion,
    $observaciones,
    $elementos,
    $fechaHora,
    $idCliente
]);

// Mostrar modal confirmando éxito
echo '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro exitoso</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap");

        body {
            font-family: "Black Ops One", cursive, Arial, sans-serif;
            margin: 0; padding: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal {
            position: fixed;
            top: 10vh;
            left: 50%;
            transform: translateX(-50%);
            background-color: #ffed00;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            text-align: center;
            z-index: 9999;
            color: #2c3e50;
            max-width: 400px;
            width: 80%;
        }
        .modal h2 {
            margin-bottom: 20px;
        }
        .modal button {
            font-family: "Black Ops One", cursive, Arial, sans-serif;
            font-weight: bold;
            background-color: #53c00a;
            border-color: #53c00a;
            color: #2c3e50;
            border-radius: 6px;
            height: 45px;
            box-shadow: 4px 8px 12px rgba(0, 0, 0, 0.25);
        }
        .modal button:hover {
            background-color: #00ff15;
        }
    </style>
</head>
<body>
    <div class="modal">
        <h2>Registro guardado exitosamente</h2>
        <button onclick="irAlMenu()">Aceptar</button>
    </div>

    <script>
        function irAlMenu() {
            window.location.href = "../../src/index.html";
        }
    </script>
</body>
</html>';
exit;
?>