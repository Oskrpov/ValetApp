<?php
date_default_timezone_set("America/Bogota");
header("Content-Type: application/json");
require_once '../configdb/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['id_cliente'])) {
            echo json_encode(["code" => 400, "msg" => "Falta el ID del cliente"]);
            exit;
        }

        $idCliente = intval($_GET['id_cliente']);
        $salidaActual = date("Y-m-d H:i:s");

        $base = new Db();
        $conn = $base->conectar();

        $sql = "UPDATE tb_registro SET Salida = :salida WHERE Id_Cliente_FK = :id_cliente ORDER BY Id_Reg DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":salida", $salidaActual);
        $stmt->bindParam(":id_cliente", $idCliente);
        
        if ($stmt->execute()) {
            echo json_encode(["code" => 200, "msg" => "Salida registrada", "salida" => $salidaActual]);
        } else {
            echo json_encode(["code" => 500, "msg" => "Error al actualizar la salida"]);
        }
    } else {
        echo json_encode(["code" => 405, "msg" => "Método no permitido"]);
    }
} catch (Exception $e) {
    echo json_encode(["code" => 500, "msg" => "Error: " . $e->getMessage()]);
}