<?php
require_once '../configdb/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['documento'])) {
            $base = new Db();
            $conn = $base->conectar();
            $documento = htmlspecialchars($_POST['documento']);

            // Buscar ID del cliente
            $sqlCliente = "SELECT IdUsuario FROM tbcliente WHERE Identificacion_Usu = :ident LIMIT 1";
            $stmtCliente = $conn->prepare($sqlCliente);
            $stmtCliente->bindValue(':ident', $documento, PDO::PARAM_STR);
            $stmtCliente->execute();

            $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                $idUsuario = $cliente['IdUsuario'];

                // Buscar historial de visitas
                $sqlHistorial = "SELECT Placa, Ubicacion_veh, observaciones, Objet_Valor, Entrada, Salida
                                 FROM tb_registro
                                 WHERE Id_Cliente_FK = :id_usuario
                                 ORDER BY Entrada DESC";
                $stmtHistorial = $conn->prepare($sqlHistorial);
                $stmtHistorial->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
                $stmtHistorial->execute();

                $visitas = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($visitas); // puede estar vacío si no hay visitas
            } else {
                // Cliente no encontrado
                echo json_encode([]);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["code" => 400, "msg" => "Falta el documento del cliente."]);
        }
    } else {
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(["code" => 405, "msg" => "Método no permitido. Usa POST."]);
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["code" => 500, "msg" => "Error en el servidor: " . $e->getMessage()]);
}