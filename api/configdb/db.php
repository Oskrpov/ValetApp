<?php
    $servidor = 'localhost';
    $base = 'valetparking';
    $usuario = 'root';
    $contrasena = '';

    $conexion = new mysqli($servidor, $usuario, $contrasena, $base);
    if ($conexion->connect_error) {
        die('No se pudo conectar con la base de datos: ' . $conexion->connect_error);
    }
    //echo 'Conexión exitosa a la base de datos.';
    echo 'Conexión exitosa a la base de datos.';
    // cierre de la conexion
    $conexion->close();
    //echo 'Conexión cerrada.';

/*class DB{
    private $servidor = 'localhost';
    private $base = 'valetparking';
    private $usuario = 'root';
    private $contrasena = '';

    public function conectar(){
        $conexion = new mysqli($this->servidor, $this->usuario, $this->contrasena, $this->base);
        if ($conexion->connect_error) {
            die('No se pudo conectar con la base de datos: ' . $conexion->connect_error);
        }
        return $conexion;
    }

}*/