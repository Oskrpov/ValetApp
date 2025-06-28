<?php
require_once '../configdb/db.php';

header("Content-Type: application/json");

// Obtener el JSON del cuerpo de la petición
$input = json_decode(file_get_contents("php://input"), true);

// Validar que venga el id_cliente
if (!isset($input['id_cliente'])) {
    echo json_encode(["status" => "error", "mensaje" => "Falta el ID del cliente"]);
    exit;
}

$idCliente = intval($input['id_cliente']);

try {
    $base = new Db();
    $conn = $base->conectar();

    // Desactivar temporalmente restricciones de integridad si fuera necesario (solo si tienes ON DELETE RESTRICT)
    // $conn->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Primero eliminar registros relacionados
    $stmt1 = $conn->prepare("DELETE FROM tb_registro WHERE Id_Cliente_FK = :id");
    $stmt1->bindParam(':id', $idCliente, PDO::PARAM_INT);
    $stmt1->execute();

    // Luego eliminar al cliente
    $stmt2 = $conn->prepare("DELETE FROM tbcliente WHERE IdUsuario = :id");
    $stmt2->bindParam(':id', $idCliente, PDO::PARAM_INT);
    $stmt2->execute();

    // Confirmar eliminación
    if ($stmt2->rowCount() > 0) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => "El cliente no fue encontrado o ya fue eliminado."]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "mensaje" => "Error en el servidor: " . $e->getMessage()]);
}
