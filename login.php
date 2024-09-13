<?php
session_start();
include('config/config.php');

$conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD, 'xvllmwa');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Modificamos la consulta para obtener también id, role, full_name y department
    $stmt = $conn->prepare("SELECT id, password, role, full_name, department FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Vinculamos los resultados
        $stmt->bind_result($id, $hashed_password, $role, $full_name, $department);
        $stmt->fetch();
        
        // Verificamos la contraseña
        if (password_verify($password, $hashed_password)) {
            // Almacenar los detalles en la sesión
            $_SESSION['user'] = [
                'id' => $id,
                'username' => $username,
                'role' => $role,
                'full_name' => $full_name,
                'department' => $department
            ];

            // Redirigir al usuario a la página de instrucciones
            header("Location: /xvllmwa/instructions");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: /xvllmwa/instructions");
        }
    } else {
        // Usuario no encontrado
        header("Location: /xvllmwa/instructions");
    }

    $stmt->close();
}

$conn->close();
?>
