<?php
$credenciales=null;   
(file_get_contents('../configdb/.key'))?$credenciales=file_get_contents('../configdb/.key'):$credenciales=file_get_contents('../configdb/db.json');
$credenciales=json_decode($credenciales,true);
?>