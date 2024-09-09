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

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $username;
            header("Location: /xvllmwa/instructions");
            exit();
        } else {
            header("Location: /xvllmwa/instructions");
        }
    } else {
        header("Location: /xvllmwa/instructions");
    }

    $stmt->close();
}

$conn->close();
?>
