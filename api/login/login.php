<?php
require_once 'loginModel.php';
header("Access-Control-Allow-Origin: *");
header("content-Type: application/json");
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            //validacion de parametros
            $_POST = json_decode(file_get_contents('php://input'), true);
            if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
                //conexion a la base de datos
                $loginModel = new loginModel();
                $resultado = $loginModel->autenticacionUsuario($_POST['usuario'], $_POST['contrasena']);
                if (count($resultado) > 0) {
                    //si el usuario existe
                    $idUser = $resultado[0]['ID_Funcionario'];
                    $nombres = $resultado[0]['Nombres'] . " " . $resultado[0]['Apellidos'];
                    //devolviendo los datos del usuario
                    header("HTTP/1.1 200 OK");
                    echo json_encode(array("code" => 200, "ID_Funcionario" => $idUser, "Nombres: " => $nombres));
                } else {
                    //si el usuario no existe
                    header("HTTP/1.1 203 Non-Authoritative Information");
                    echo json_encode(array("code" => 203, "msg" => "Las credenciales no son validas"));
                }
            } else {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(array("code" => 400, "msg" => "faltan parametros necesarios"));
            }
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array("code" => 500, "msg" => "Error en el servidor: " . $e->getMessage()));
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array("code" => 400, "msg" => "Solicitud incorrecta por parte del cliente"));
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("code" => 500, "msg" => "Error en el servidor \n" . $e->getMessage()));
}
