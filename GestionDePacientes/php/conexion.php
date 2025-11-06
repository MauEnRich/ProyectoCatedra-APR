<?php
$host = "localhost";
$usuario = "root";
$clave = ""; 
$bd = "gestion_pacientes";
$port = 3306;


$conexion = new mysqli($host, $usuario, $clave, $bd, $port);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
