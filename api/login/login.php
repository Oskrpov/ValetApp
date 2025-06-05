<?php
require_once 'loginModel.php';
session_start(); // ← NECESARIO para usar $_SESSION

header("Access-Control-Allow-Origin: *");
header("content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_POST = json_decode(file_get_contents('php://input'), true);
        if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
            $loginModel = new loginModel();
            $resultado = $loginModel->autenticacionUsuario($_POST['usuario'], $_POST['contrasena']);

            if (count($resultado) > 0) {
                // Datos del funcionario
                $idUser = $resultado[0]['ID_Funcionario'];
                $nombres = $resultado[0]['Nombres'] . " " . $resultado[0]['Apellidos'];

                // ✔️ Guardar en sesión
                $_SESSION['id_funcionario'] = $idUser;
                //$_SESSION['nombre_funcionario'] = $nombres;

                // Devolver respuesta JSON
                header("HTTP/1.1 200 OK");
                echo json_encode([
                    "code" => 200,
                    "ID_Funcionario" => $idUser,
                    "Nombres" => $nombres
                ]);
            } else {
                header("HTTP/1.1 203 Non-Authoritative Information");
                echo json_encode(["code" => 203, "msg" => "Las credenciales no son válidas"]);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["code" => 400, "msg" => "Faltan parámetros necesarios"]);
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(["code" => 400, "msg" => "Solicitud incorrecta por parte del cliente"]);
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["code" => 500, "msg" => "Error en el servidor: " . $e->getMessage()]);
}
