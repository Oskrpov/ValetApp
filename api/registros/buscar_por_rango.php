<?php
require_once '../configdb/db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fechaInicio = $_POST['fecha_inicio'] ?? '';
        $fechaFin = $_POST['fecha_fin'] ?? '';

        if (empty($fechaInicio) || empty($fechaFin)) {
            echo json_encode([]);
            exit;
        }

        $base = new Db();
        $conn = $base->conectar();

        $sql = "
            SELECT c.Nombres_Usu, c.Apellidos_Usu, c.Identificacion_Usu,
                   r.Placa, r.Ubicacion_veh, r.observaciones, r.Objet_Valor, r.Entrada, r.Salida
            FROM tb_registro r
            INNER JOIN tbcliente c ON r.Id_Cliente_FK = c.IdUsuario
            WHERE DATE(r.Entrada) BETWEEN :inicio AND :fin
            ORDER BY r.Entrada DESC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":inicio", $fechaInicio);
        $stmt->bindParam(":fin", $fechaFin);
        $stmt->execute();

        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($datos);
    } else {
        http_response_code(405);
        echo json_encode(["msg" => "Método no permitido"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["msg" => "Error del servidor", "error" => $e->getMessage()]);
}

