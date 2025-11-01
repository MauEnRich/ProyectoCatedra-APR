<?php
header('Content-Type: application/json');
include "conexion.php";

$input = json_decode(file_get_contents("php://input"), true);

$campos = [
  "id_paciente", "grupo_sanguineo", "alergias", "enfermedades_cronicas", "medicamentos_actuales",
  "antecedentes_familiares", "antecedentes_personales", "vacunas", "fecha_creacion",
  "fecha_ultima_actualizacion", "motivo_consulta", "diagnostico", "tratamiento",
  "medico_responsable", "area_atencion", "tipo_atencion", "observaciones_medicas"
];

$valores = [];
foreach ($campos as $campo) {
  $valores[$campo] = isset($input[$campo]) ? "'" . $conexion->real_escape_string($input[$campo]) . "'" : "NULL";
}

$sql = "INSERT INTO expediente (" . implode(",", $campos) . ") VALUES (" . implode(",", $valores) . ")";

if ($conexion->query($sql) === TRUE) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false, "error" => $conexion->error]);
}
?>
