<?php
session_start();

// Conexión a la base de datos (puedes poner tus propios datos si son diferentes)
$servername = "localhost";
$username = "root";
$password = "";
$database = "valetparking";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombres   = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$documento = $_POST['documento'];
$telefono  = $_POST['telefono'];
$placa     = $_POST['placa'];

// Obtener el ID del funcionario desde la sesión
if (!isset($_SESSION['id_funcionario'])) {
    die("Error: No hay un funcionario autenticado.");
}
$idFuncionario = $_SESSION['id_funcionario'];

// Preparar y ejecutar la inserción
$stmt = $conn->prepare("INSERT INTO tbcliente (Nombres_Usu, Apellidos_Usu, Identificacion_Usu, Telefono_usu, placa_usu, Id_funcionario_FK) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $nombres, $apellidos, $documento, $telefono, $placa, $idFuncionario);

if ($stmt->execute()) {
    echo "Cliente registrado correctamente.";
} else {
    echo "Error al registrar cliente: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
