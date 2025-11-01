<?php
header('Content-Type: application/json');
include "conexion.php";

$sql = "SELECT * FROM citas ORDER BY fecha ASC, hora ASC";
$resultado = $conexion->query($sql);

$citas = [];
while ($fila = $resultado->fetch_assoc()) {
  $citas[] = $fila;
}

echo json_encode($citas);
?>
