<?php
header('Content-Type: application/json');
include "conexion.php";

$sql = "SELECT * FROM pacientes";
$resultado = $conexion->query($sql);

$pacientes = [];
while ($fila = $resultado->fetch_assoc()) {
    $pacientes[] = $fila;
}

echo json_encode($pacientes);
?>
