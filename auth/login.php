<?php

/**
 * Este script maneja el inicio de sesión de usuarios.
 * 1. Inicia la sesión del usuario.
 * 2. Conecta a la base de datos y verifica las credenciales proporcionadas (nombre de usuario y contraseña).
 * 3. Si las credenciales son correctas, se guarda la información del usuario en la sesión.
 * 4. Redirige al usuario a la página de instrucciones en caso de éxito.
 * 5. Si las credenciales son incorrectas, redirige a la página 'forbidden'.
 */
session_start();
require('../config/config.php');
$conn = connect_to_db();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password, role, full_name, department FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role, $full_name, $department);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = [
                'id' => $id,
                'username' => $username,
                'role' => $role,
                'full_name' => $full_name,
                'department' => $department
            ];
            header("Location: $WEBROOT/instructions");
            exit();
        } else {
            header("Location: $WEBROOT/forbidden");
        }
    } else {
        header("Location: $WEBROOT/forbidden");
    }

    $stmt->close();
}

$conn->close();