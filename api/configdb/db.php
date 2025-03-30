<?php
$credenciales=file_get_contents('..//../config/db.json');
$credenciales=json_decode($credenciales,true);
class Db{
    private $servidor;
    private $base;
    private $usuario;
    private $contrasena;
    public function __construct(){
        global $credenciales;
        $this->servidor=$credenciales['host'];
        $this->base=$credenciales['db'];
        $this->usuario=$credenciales['user'];
        $this->contrasena=$credenciales['pass'];
    }
    public function conectar(){
        $conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->base);
        if ($conexion->connect_error) {
            die('No se pudo conectar con la base de datos: ' . $conexion->connect_error);
        }
        echo 'Conexión exitosa a la base de datos.';
            return $conexion;
    }

}