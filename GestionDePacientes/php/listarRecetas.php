<?php

//El script JS debe llamar a este endpoint al cargar la página de recetas
// para llenar una tabla similar a las de citas y expedientes.

// listarRecetas.php
header('Content-Type: application/json');
include "conexion.php";
include 'control_acceso.php';
verificarAcceso(null); // Permite el acceso a cualquier usuario logueado

// COMENTARIO FRONTEND:
// JS recibirá un array de objetos con detalles (medicamento, paciente, médico) para mostrar en una tabla.

// ... (El código de listarRecetas.php que le proporcioné anteriormente)
$sql = "
SELECT 
    r.id_receta, 
    r.id_paciente, 
    CONCAT(p.nombre, ' ', p.apellido) AS nombre_paciente,
    r.medicamento, 
    r.dosis, 
    r.frecuencia, 
    r.duracion,
    r.fecha_creacion,
    u.usuario AS medico_responsable
FROM recetas r
JOIN pacientes p ON r.id_paciente = p.id_paciente
JOIN usuarios u ON r.medico_id = u.id_usuario 
ORDER BY r.fecha_creacion DESC";

$resultado = $conexion->query($sql);

$recetas = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $recetas[] = $fila;
    }
    echo json_encode($recetas);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Error al obtener recetas: " . $conexion->error]);
}

$conexion->close();
?>