<?php
session_start();

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['IdUsuario'])) {
    $_SESSION['IdUsuario'] = $data['IdUsuario'];
    echo json_encode([
        "code" => 200,
        "msg" => "Cliente guardado en sesión"
    ]);
} else {
    echo json_encode([
        "code" => 400,
        "msg" => "IdUsuario no recibido"
    ]);
}
?>