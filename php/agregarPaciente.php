<?php
header('Content-Type: application/json');
include "conexion.php";

$input = json_decode(file_get_contents("php://input"), true);

$nombre = substr(trim($input["nombre"]), 0, 100);
$apellido = substr(trim($input["apellido"]), 0, 100);
$edad = isset($input["edad"]) && $input["edad"] !== "" ? intval($input["edad"]) : NULL;
$genero = isset($input["genero"]) && in_array($input["genero"], ["M","F"]) ? $input["genero"] : NULL;
$telefono = isset($input["telefono"]) ? substr(trim($input["telefono"]), 0, 20) : NULL;
$direccion = isset($input["direccion"]) ? substr(trim($input["direccion"]), 0, 200) : NULL;

if (empty($nombre) || empty($apellido)) {
    echo json_encode(["success" => false, "error" => "Nombre y apellido son obligatorios"]);
    exit;
}


$sql = "INSERT INTO pacientes (nombre, apellido, edad, genero, telefono, direccion) VALUES 
('$nombre', '$apellido', " . ($edad ?? "NULL") . ", " . ($genero !== NULL ? "'$genero'" : "NULL") . ", " . ($telefono !== NULL ? "'$telefono'" : "NULL") . ", " . ($direccion !== NULL ? "'$direccion'" : "NULL") . ")";

if ($conexion->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conexion->error]);
}
?>
