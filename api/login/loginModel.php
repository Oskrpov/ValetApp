<?php
require_once '../configdb/db.php';
class loginModel
{
    private $conn;
    public function __construct()
    {
        $base = new Db();
        $this->conn = $base->conectar();
    }
    public function autenticacionUsuario($user, $pass)
    {
        try {
            $sql = "SELECT `ID_Funcionario`,`Nombres`,`Apellidos` FROM `tbfuncionarios` WHERE Perfil=:username and Contrasena=:password_user";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindvalue(':username', $user, PDO::PARAM_STR);
            $stmt->bindvalue(':password_user', $pass, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return $stmt->fetchALL(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }
}
