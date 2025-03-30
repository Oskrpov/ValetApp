<?php
require_once 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    try{
        $base = new Db();
        $conn = $base->conectar();
    header("HTTP/1.1 200 OK");
    echo json_encode(array("Code"=>200, "msg" => "Conexión exitosa a la base de datos."));
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array("Code"=>500, "msg" => "Error al conectar a la base de datos: " . $e->getMessage()));
        exit;
    }
}else{
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(array("Code"=>400, "msg" => "Solicitud incorrecta por parte del cliente"));
}
?>