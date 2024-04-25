<?php 

$server = "localhost";
$user = "root";
$password = "";
$db = "punto_venta_tenis";
//$port = 3308;


$conexion = new mysqli($server, $user, $password, $db);

if($conexion->connect_error){
    die("La conexión con la BD ha fallado.".conexion->connect_error);
}

?>