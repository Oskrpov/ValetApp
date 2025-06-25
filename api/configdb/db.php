<?php
include_once 'llaves.php';
date_default_timezone_set('America/Bogota');
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
        try{
        $conexion=new PDO("mysql:host=$this->servidor;dbname=$this->base;charset=utf8",$this->usuario,$this->contrasena);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
    } catch(PDOException $e){
            return "Error de conexion: " . $e->getMessage();
        }
    }
}