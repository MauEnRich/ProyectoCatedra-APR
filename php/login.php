<?php
header('Content-Type: application/json');
include "conexion.php";


$input = json_decode(file_get_contents("php://input"), true);
$usuario = $input["usuario"];
$password = $input["password"];


$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password' LIMIT 1";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    $fila = $result->fetch_assoc();
    echo json_encode(["success" => true, "rol" => $fila["rol"]]);
} else {
    echo json_encode(["success" => false]);
}
?>
