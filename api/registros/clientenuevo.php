<?php
session_start(); // Muy importante para usar $_SESSION

// Mostrar errores para depurar
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar que el funcionario haya iniciado sesión
if (!isset($_SESSION['id_funcionario'])) {
    die("No se ha iniciado sesión. No se puede registrar cliente.");
}

// Obtener el id del funcionario desde la sesión
$id_funcionario = $_SESSION['id_funcionario'];

// Configuración de la base de datos
$host = "127.0.0.1";
$usuario = "root";
$contrasena = "";
$basedatos = "valetparking";

// Conexión a la base de datos
$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar que se recibió por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario (limpiarlos si quieres)
    $nombres = $_POST['nombres'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $documento = $_POST['documento'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $placa = $_POST['placa'] ?? '';

    // Validar campos vacíos (básico)
    if ($nombres && $apellidos && $documento && $telefono && $placa) {
        // Preparar sentencia SQL con el id_funcionario de sesión
        $stmt = $conn->prepare("INSERT INTO tbcliente 
            (Nombres_Usu, Apellidos_Usu, Identificacion_Usu, Telefono_usu, placa_usu, Id_funcionario_FK) 
            VALUES (?, ?, ?, ?, ?, ?)");

        // Asegúrate de usar los tipos correctos: "ssiiii"
        $stmt->bind_param("ssiiis", $nombres, $apellidos, $documento, $telefono, $placa, $id_funcionario);

        // Ejecutar e informar
        if ($stmt->execute()) {
            echo "Registro guardado exitosamente.";
        } else {
            echo "Error al guardar el registro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

$conn->close();
?>
