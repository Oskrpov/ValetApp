<?php
date_default_timezone_set("America/Bogota");
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

// Obtener el ID del funcionario desde la sesión
if (!isset($_SESSION['id_funcionario'])) {
    die("Error: No hay un funcionario autenticado.");
}
$idFuncionario = $_SESSION['id_funcionario'];

// Preparar y ejecutar la inserción
$stmt = $conn->prepare("INSERT INTO tbcliente (Nombres_Usu, Apellidos_Usu, Identificacion_Usu, Telefono_usu, Id_funcionario_FK) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $nombres, $apellidos, $documento, $telefono, $idFuncionario);
if ($stmt->execute()) {
    // ✅ GUARDAR EL ID DEL CLIENTE EN LA SESIÓN
    $_SESSION['IdUsuario'] = $stmt->insert_id;
    // 🔴 CORRECCIÓN: Redirigir usando Location y añadir exit
    header("Location: ../../src/novedades_vehiculo.html");
    exit;
} else {
    echo "Error al registrar cliente: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>