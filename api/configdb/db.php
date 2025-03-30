<?php
include_once 'llaves.php';
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
        //echo 'Conexión exitosa a la base de datos.';
            return $conexion;
    }
//esta es una prueba de un commit y un push desde visual studio code
}