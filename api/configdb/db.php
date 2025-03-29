<?php
  /*  $servidor = '127.0.0.1';
    $base = 'valetparking';
    $usuario = 'root';
    $contrasena = '';

    $conexion = new mysqli($servidor, $usuario, $contrasena, $base);
    if ($conexion->connect_error) {
        die('No se pudo conectar con la base de datos: ' . $conexion->connect_error);
    }
    //Conexión exitosa a la base de datos.
    echo 'Conexión exitosa a la base de datos.';
    // cierre de la conexion
    $conexion->close();
    //echo 'Conexión cerrada.';
*/
class Db{
    private $servidor = '127.0.0.1';
    private $base = 'valetparking';
    private $usuario = 'root';
    private $contrasena = '';

    public function conectar(){
        $conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->base);
        if ($conexion->connect_error) {
            die('No se pudo conectar con la base de datos: ' . $conexion->connect_error);
        }
        echo 'Conexión exitosa a la base de datos.';
            return $conexion;
    }

}