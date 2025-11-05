<?php

//Crear un archivo JS global para manejar el cierre de sesión (cerrarSesion.php) 
//y la verificación de rol (localStorage.getItem('rol')) antes de cargar el contenido 
//de las páginas.


// control_acceso.php
// COMENTARIO FRONTEND:
// 1. Crear un script JS global para llamar a verificarAcceso() en el backend.
// 2. Si el backend devuelve 401 o 403, redirigir a index.html (login).

function verificarAcceso($rol_requerido = null) {
    // Es CRÍTICO iniciar la sesión en cada script PHP que utilice este control.
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // 1. Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        http_response_code(401); // No autorizado
        echo json_encode(["success" => false, "error" => "Acceso denegado. Se requiere iniciar sesión."]);
        exit;
    }
    
    // 2. Si se requiere un rol específico
    if ($rol_requerido !== null) {
        $rol_usuario = $_SESSION['rol'] ?? 'invitado';
        
        // Verificar si el rol del usuario coincide con el rol requerido
        if ($rol_usuario !== $rol_requerido) {
            http_response_code(403); // Prohibido
            echo json_encode(["success" => false, "error" => "Acceso prohibido. Rol insuficiente."]);
            exit;
        }
    }
    // Si pasa ambas verificaciones, el script continúa
    return true;
}

// Endpoint para cerrar sesión (a usar desde el frontend)
if (basename($_SERVER['PHP_SELF']) == 'cerrarSesion.php') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = array();
    session_destroy();
    setcookie("PHPSESSID", "", time() - 3600, "/");
    echo json_encode(["success" => true, "mensaje" => "Sesión cerrada."]);
    exit;
}
?>