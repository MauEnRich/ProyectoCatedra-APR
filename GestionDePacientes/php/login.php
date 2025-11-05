<?php
//Este archivo ahora configura la sesión PHP. El script login.js no necesita cambios importantes, pero 
// la lógica de roles en dashboard.js debe complementarse con la verificación de sesión en el backend 
//(Ver control_acceso.php). IMPORTANTE: La tabla usuarios necesita la columna password_hash con contraseñas 
// hasheadas.

// login.php (VERSION SEGURA Y REFACTORIZADA CON SENTENCIAS PREPARADAS Y HASHING)
header('Content-Type: application/json');
include 'conexion.php';
session_start(); // Inicia la sesión

$input = json_decode(file_get_contents('php://input'), true);
$usuario_input = $input['usuario'] ?? '';
$password_input = $input['password'] ?? '';

// 1. Consulta segura con Sentencia Preparada (Previene SQL Injection)
$sql = "SELECT id_usuario, password_hash, rol FROM usuarios WHERE usuario = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario_input);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $fila = $result->fetch_assoc();
    $password_hash_bd = $fila['password_hash']; 

    // 2. Verificar el Hash de la Contraseña (Usa password_verify())
    if (password_verify($password_input, $password_hash_bd)) {
        // AUTENTICACIÓN EXITOSA: Configurar variables de sesión
        $_SESSION['loggedin'] = true;
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['rol'] = $fila['rol']; // Ejemplo: 'admin', 'medico', 'recepcion'
        $_SESSION['usuario'] = $usuario_input;

        echo json_encode(['success' => true, 'rol' => $fila['rol']]);
    } else {
        // Contraseña incorrecta
        echo json_encode(['success' => false, 'error' => 'Usuario o contraseña incorrectos.']);
    }
} else {
    // Usuario no encontrado
    echo json_encode(['success' => false, 'error' => 'Usuario o contraseña incorrectos.']);
}
$stmt->close();
$conexion->close();
?>