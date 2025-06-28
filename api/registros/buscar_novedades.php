<?php
require_once '../configdb/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['id_cliente'])) {
            http_response_code(400);
            echo json_encode(["code" => 400, "msg" => "Falta el parámetro id_cliente"]);
            exit;
        }

        $idCliente = intval($_GET['id_cliente']);
        $base = new Db();
        $conn = $base->conectar();

        $sql = "SELECT Placa, Imagen_novedad, Ubicacion_veh, observaciones, Objet_Valor, Entrada, Salida
                FROM tb_registro
                WHERE Id_Cliente_FK = :id_cliente
                ORDER BY Id_Reg DESC
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($datos) {
            echo json_encode(["code" => 200, "datos" => $datos]);
        } else {
            echo json_encode(["code" => 404, "msg" => "No se encontraron novedades."]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["code" => 405, "msg" => "Método no permitido."]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["code" => 500, "msg" => "Error en el servidor: " . $e->getMessage()]);
}
