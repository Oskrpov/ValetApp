<?php
require_once 'db.php';
try{
    $base = new Db();
    $conn = $base->conectar();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}
?>