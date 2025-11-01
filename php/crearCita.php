<?php
header('Content-Type: application/json');
include "conexion.php";

$input = json_decode(file_get_contents("php://input"), true);

$paciente_id = intval($input["paciente_id"]);
$fecha = $conexion->real_escape_string($input["fecha"]);
$hora = $conexion->real_escape_string($input["hora"]);
$motivo = $conexion->real_escape_string($input["motivo"]);
$estado = $conexion->real_escape_string($input["estado"]);

$sql = "INSERT INTO citas (paciente_id, fecha, hora, motivo, estado) VALUES ($paciente_id, '$fecha', '$hora', '$motivo', '$estado')";

if ($conexion->query($sql) === TRUE) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "error" => $conexion->error]);
}
?>
