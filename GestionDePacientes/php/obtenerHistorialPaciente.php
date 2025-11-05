<?php

//Crear la interfaz historialMedico.html. Debe tener un campo de búsqueda por id_paciente 
// o seleccionar de una lista. El JS debe enviar el id_paciente por POST a este endpoint 
// y luego renderizar los cuatro arrays de datos (paciente, citas, expedientes, recetas) 
// en secciones separadas de la página.

// obtenerHistorialPaciente.php
header('Content-Type: application/json');
include "conexion.php";
include 'control_acceso.php';
verificarAcceso('medico'); // EJEMPLO: El historial médico solo es accesible por el rol 'medico'

// COMENTARIO FRONTEND:
// El JS debe enviar el id_paciente para esta consulta.
// La respuesta JSON contiene 4 secciones (paciente, citas, expedientes, recetas) para renderizar.

$input = json_decode(file_get_contents("php://input"), true);
$id_paciente = $input['id_paciente'] ?? null;

if (empty($id_paciente)) {
    echo json_encode(["success" => false, "error" => "ID de paciente es obligatorio."]);
    exit;
}

$historial = [
    "paciente" => null,
    "citas" => [],
    "expedientes" => [],
    "recetas" => []
];

// 1. Obtener Datos del Paciente (Uso de Sentencias Preparadas)
$sql_paciente = "SELECT * FROM pacientes WHERE id_paciente = ?";
$stmt_paciente = $conexion->prepare($sql_paciente);
$stmt_paciente->bind_param("i", $id_paciente);
$stmt_paciente->execute();
$resultado_paciente = $stmt_paciente->get_result();
$historial["paciente"] = $resultado_paciente->fetch_assoc();
$stmt_paciente->close();

if (!$historial["paciente"]) {
    echo json_encode(["success" => false, "error" => "Paciente no encontrado."]);
    exit;
}

// 2. Obtener Citas (Uso de Sentencias Preparadas)
$sql_citas = "SELECT id, fecha, hora, motivo, estado FROM citas WHERE paciente_id = ? ORDER BY fecha DESC";
$stmt_citas = $conexion->prepare($sql_citas);
$stmt_citas->bind_param("i", $id_paciente);
$stmt_citas->execute();
$resultado_citas = $stmt_citas->get_result();
while ($fila = $resultado_citas->fetch_assoc()) {
    $historial["citas"][] = $fila;
}
$stmt_citas->close();

// 3. Obtener Expedientes (Uso de Sentencias Preparadas)
$sql_exp = "SELECT * FROM expediente WHERE id_paciente = ? ORDER BY id_expediente DESC";
$stmt_exp = $conexion->prepare($sql_exp);
$stmt_exp->bind_param("i", $id_paciente);
$stmt_exp->execute();
$resultado_exp = $stmt_exp->get_result();
while ($fila = $resultado_exp->fetch_assoc()) {
    $historial["expedientes"][] = $fila;
}
$stmt_exp->close();

// 4. Obtener Recetas (Uso de Sentencias Preparadas)
$sql_recetas = "SELECT id_receta, medicamento, dosis, frecuencia, duracion, fecha_creacion FROM recetas WHERE id_paciente = ? ORDER BY fecha_creacion DESC";
$stmt_recetas = $conexion->prepare($sql_recetas);
$stmt_recetas->bind_param("i", $id_paciente);
$stmt_recetas->execute();
$resultado_recetas = $stmt_recetas->get_result();
while ($fila = $resultado_recetas->fetch_assoc()) {
    $historial["recetas"][] = $fila;
}
$stmt_recetas->close();

echo json_encode(["success" => true, "historial" => $historial]);
$conexion->close();
?>