<?php
require_once '../configdb/db.php';
session_start(); // ← NECESARIO para usar $_SESSION

header("Access-Control-Allow-Origin: *");
header("content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['documento'])) {
            $base = new Db();
            $conn = $base->conectar();
            $documento = htmlspecialchars($_GET['documento']);
            //var_dump($documento); // Para depurar, eliminar en producción
            try {
                $sql = "SELECT IdUsuario, Nombres_Usu, Apellidos_Usu, Identificacion_Usu FROM tbcliente WHERE Identificacion_Usu = :ident LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->bindvalue(':ident', $documento, PDO::PARAM_STR);
                if ($stmt->execute()) {
                 //   return $stmt->fetchALL(PDO::FETCH_ASSOC);
                 header("HTTP/1.1 200 OK");
                echo json_encode([
                    "code" => 200,
                    "datos" => $stmt->fetchALL(PDO::FETCH_ASSOC),
                    "msg" => "Consulta exitosa"
                ]);
                } else {
                    return false;
                }
            } catch (Exception $e) {
                throw new Exception("Error en la consulta: " . $e->getMessage());
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["code" => 400, "msg" => "Solicitud incorrecta por parte del cliente"]);
        }
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["code" => 500, "msg" => "Error en el servidor: " . $e->getMessage()]);
}
