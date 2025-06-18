<?php
require_once '../configdb/db.php';
session_start(); // Para usar $_SESSION

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
            $stmt->execute();

            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                $_SESSION['IdUsuario'] = $cliente['IdUsuario'];

                // Buscar placas asociadas en tb_registro
                $sqlPlacas = "SELECT Placa FROM tb_registro WHERE Id_Cliente_FK = :id";
                $stmtPlacas = $conn->prepare($sqlPlacas);
                $stmtPlacas->bindValue(':id', $cliente['IdUsuario'], PDO::PARAM_INT);
                $stmtPlacas->execute();
                $placas = $stmtPlacas->fetchAll(PDO::FETCH_COLUMN);

                header("HTTP/1.1 200 OK");
                echo json_encode([
                    "code" => 200,
                    "datos" => [$cliente],
                    "placas" => $placas,
                    "msg" => "Consulta exitosa"
                ]);
            } else {
                echo json_encode(["code" => 404, "msg" => "Cliente no encontrado"]);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(["code" => 400, "msg" => "Documento requerido"]);
        }
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["code" => 500, "msg" => "Error: " . $e->getMessage()]);
}

