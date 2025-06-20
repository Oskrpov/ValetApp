<?php
require_once '../configdb/db.php';
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['documento'])) {
            $base = new Db();
            $conn = $base->conectar();
            $documento = htmlspecialchars($_GET['documento']);

            $sql = "SELECT IdUsuario, Nombres_Usu, Apellidos_Usu, Identificacion_Usu FROM tbcliente WHERE Identificacion_Usu = :ident LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':ident', $documento, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($datos) > 0) {
                    // Guardar en sesión el IdUsuario
                    $_SESSION['IdUsuario'] = $datos[0]['IdUsuario'];

                    header("HTTP/1.1 200 OK");
                    echo json_encode([
                        "code" => 200,
                        "datos" => $datos,
                        "msg" => "Consulta exitosa"
                    ]);
                } else {
                    header("HTTP/1.1 404 Not Found");
                    echo json_encode([
                        "code" => 404,
                        "msg" => "Cliente no encontrado"
                    ]);
                }
            } else {
                throw new Exception("Error al ejecutar la consulta.");
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["code" => 400, "msg" => "Solicitud incorrecta. Falta el documento."]);
        }
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["code" => 500, "msg" => "Error en el servidor: " . $e->getMessage()]);
}
