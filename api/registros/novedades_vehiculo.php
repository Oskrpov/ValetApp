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

// Obtener ID del cliente desde la sesión (debe haberse guardado antes)
$idCliente = $_SESSION['IdUsuario'] ?? null;

if (!$idCliente) {
    die("No se encontró el ID del cliente en sesión.");
}

// Buscar la placa asociada al cliente en tbcliente
$stmt = $pdo->prepare("SELECT placa_usu FROM tbcliente WHERE IdUsuario = ?");
$stmt->execute([$idCliente]);
$cliente = $stmt->fetch();

if (!$cliente) {
    die("No se encontró el cliente con el ID proporcionado.");
}

$placa = $cliente['placa_usu'];

// Obtener y validar datos del formulario
$ubicacion = $_POST['ubicacion'] ?? '';
$elementos = $_POST['elementos'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';
$imagen_base64 = $_POST['imagen_canvas'] ?? '';

if (!$ubicacion || !$elementos || !$imagen_base64) {
    die("Faltan datos obligatorios.");
}

// Guardar imagen en el servidor
$directorio = '../src/sources/img_novedades/';
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
$stmtInsert = $pdo->prepare("INSERT INTO tb_registro (Placa, imagen_novedad, Ubicacion_veh, Objet_Valor, Entrada, Id_Cliente_FK)
    VALUES (?, ?, ?, ?, ?, ?)");

$stmtInsert->execute([
    $placa,
    $rutaParaDB,
    $ubicacion,
    $elementos,
    $fechaHora,
    $idCliente
]);

//echo "Registro guardado correctamente.";
header("Location: ../../src/registroexitoso.html"); // Puedes redirigir si lo necesitas
exit;
