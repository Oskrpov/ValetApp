<?php
require_once '../configdb/db.php';
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Verificar si hay sesión activa con ID de usuario
if (!isset($_SESSION['IdUsuario'])) {
    http_response_code(401); // No autorizado
    echo json_encode([
        "code" => 401,
        "msg" => "Sesión no iniciada o ID de usuario no disponible."
    ]);
    exit;
}

$idUsuario = $_SESSION['IdUsuario'];

// Validar que llegaron los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si vienen por JSON (fetch con headers), se usa php://input
    $input = json_decode(file_get_contents("php://input"), true);

    $nombres = $input['nombres'] ?? null;
    $apellidos = $input['apellidos'] ?? null;
    $documento = $input['documento'] ?? null;

    if ($nombres && $apellidos && $documento) {
        try {
            $base = new Db();
            $conn = $base->conectar();

            $sql = "UPDATE tbcliente SET Nombres_Usu = :nombres, Apellidos_Usu = :apellidos, Identificacion_Usu = :documento WHERE IdUsuario = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombres', $nombres, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':documento', $documento, PDO::PARAM_STR);
            $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode([
                    "code" => 200,
                    "msg" => "Datos actualizados correctamente."
                ]);
            } else {
                echo json_encode([
                    "code" => 500,
                    "msg" => "Error al actualizar los datos."
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "code" => 500,
                "msg" => "Error en el servidor: " . $e->getMessage()
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "code" => 400,
            "msg" => "Faltan datos obligatorios (nombres, apellidos o documento)."
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "code" => 405,
        "msg" => "Método no permitido. Se espera POST."
    ]);
}
?>
