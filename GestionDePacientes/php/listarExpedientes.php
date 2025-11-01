<?php
header('Content-Type: application/json');
include "conexion.php";

$sql = "SELECT id_expediente, id_paciente, grupo_sanguineo, motivo_consulta, diagnostico, tipo_atencion FROM expediente ORDER BY fecha_creacion DESC";
$resultado = $conexion->query($sql);

$expedientes = [];
while ($fila = $resultado->fetch_assoc()) {
  $expedientes[] = $fila;
}

echo json_encode($expedientes);
?>
