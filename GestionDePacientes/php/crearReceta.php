<?php

//crearReceta.html con un formulario que solicite id_paciente, medicamento, 
// dosis, frecuencia, duracion. El script JS debe enviar estos datos por 
// POST a este endpoint.

// crearReceta.php
header('Content-Type: application/json');
include 'conexion.php'; 
include 'control_acceso.php';
verificarAcceso('medico'); // EJEMPLO: Solo los médicos pueden crear recetas

// COMENTARIO FRONTEND:
// El JS debe enviar por POST: id_paciente, medicamento, dosis, frecuencia, duracion, medico_id.
// El medico_id puede obtenerse de la variable de sesión (lo más seguro).

// ... (El código de crearReceta.php que le proporcioné anteriormente)
$input = json_decode(file_get_contents("php://input"), true);

$id_paciente = $input['id_paciente'] ?? null;
$medicamento = $input['medicamento'] ?? '';
$dosis = $input['dosis'] ?? '';
$frecuencia = $input['frecuencia'] ?? '';
$duracion = $input['duracion'] ?? '';
// OBTENER el ID del médico de la SESIÓN (lo más seguro)
$medico_id = $_SESSION['id_usuario'] ?? 1; // ID de usuario logueado

if (empty($id_paciente) || empty($medicamento) || empty($dosis) || empty($frecuencia)) {
    echo json_encode(["success" => false, "error" => "Campos obligatorios faltantes."]);
    exit;
}

// Usando Sentencias Preparadas
$sql = "INSERT INTO recetas (id_paciente, medicamento, dosis, frecuencia, duracion, medico_id, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    echo json_encode(["success" => false, "error" => "Error al preparar la consulta: " . $conexion->error]);
    exit;
}

$stmt->bind_param("issssi", $id_paciente, $medicamento, $dosis, $frecuencia, $duracion, $medico_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id_receta" => $conexion->insert_id]);
} else {
    echo json_encode(["success" => false, "error" => "Error al guardar la receta: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>