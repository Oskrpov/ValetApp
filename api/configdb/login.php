<?php
require_once 'db.php';
header("Access-Control-Allow-Origin: *");
header("content-Type: application/json");
try{
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
     try{
        //validacion de parametros
       $_POST = json_decode(file_get_contents('php://input'), true);
        if(isset($_POST['usuario']) && isset($_POST['contrasena'])){
            //conexion a la base de datos
            $base = new Db();
            $conn = $base->conectar();
            $user = $_POST['usuario'];
            $pass = $_POST['contrasena'];
            $sql = "SELECT `ID_Funcionario`,`Nombres`,`Apellidos` FROM `tbfuncionarios` WHERE Perfil=:username and Contrasena=MD5(:password_user)";
            $stmt = $conn->prepare($sql);
            $stmt->bindvalue(':username', $user, PDO::PARAM_STR);
            $stmt->bindvalue(':password_user', $pass, PDO::PARAM_STR);
            if($stmt->execute()){
                $resultado = $stmt->fetchALL(PDO::FETCH_ASSOC);
                if(count($resultado)>0){
                    //si el usuario existe
                    $id = $resultado[0]['ID_Funcionario'];
                    $nombres = $resultado[0]['Nombres']. " ".$resultado[0]['Apellidos'];
                    //devolviendo los datos del usuario
                    header("HTTP/1.1 200 OK");
                    echo json_encode(array("Usuario encontrado!!!", "ID_Funcionario:"=>$id, "Nombres: "=>$nombres));
                }else{
                    //si el usuario no existe
                    header("HTTP/1.1 203 Non-Authoritative Information");
                    echo json_encode(array("Code"=>200, "msg" => "Las credenciales no son validas"));
                }
        }else{
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array("Code"=>400, "msg" => "error al ejecutar la consulta"));
        }
    } else{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array("Code"=>400, "msg" => "faltan parametros necesarios"));
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(array("Code"=>500, "msg" => "Error en el servidor: " . $e->getMessage()));
    }
}   else{
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(array("code"=>400, "msg" => "Solicitud incorrecta por parte del cliente"));
    }
} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("code"=>500, "msg" => "Error en el servidor \n".$e->getMessage()));
}
?>