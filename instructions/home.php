<?php

$conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD, 'xvllmwa');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userData = null;

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['username'];
    $stmt = $conn->prepare("SELECT role, department FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<div class="hero-body">
    <div class="container">
        <?php if (isset($_SESSION['user']) && $userData): ?>
            <div class="card welcome-card">
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <span class="welcome-icon">
                                <i class="fas fa-user-circle"></i>
                            </span>
                        </div>
                        <div class="media-content">
                            <h1 class="title is-3">¡Bienvenido a <strong>xCorp</strong>, <?php echo ucfirst($_SESSION['user']['username']); ?>!</h1> <!-- Acceder al 'username' -->
                            <p class="subtitle is-5">Estamos orgullosos de tenerte a bordo.</p>
                            <br>
                        </div>
                    </div>
                    <div class="content">
                        <p class="has-text-weight-bold">
                            En tu rol como <span class="tag is-primary is-light"><?php echo $userData['role']; ?></span> del departamento <span class="tag is-info is-light"><?php echo $userData['department']; ?></span>, tu contribución es crucial para nuestro éxito.
                        </p>
                        <p>Disfruta de la ayuda de nuestros asistentes virtuales, diseñados para facilitar tus tareas y mejorar la productividad en la empresa.</p>
                        <br>
                        <p><strong>xCorp</strong> siempre avanza con tecnología de vanguardia, y tú eres parte de esta transformación.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="notification is-warning">
                <h1 class="title is-3">Acceso Denegado</h1>
                <p>Lo siento, usuario. Solo el personal autorizado puede acceder a los asistentes virtuales de <strong>xCorp</strong>.</p>
                <br>
                <p>Recuerda seguir la guía de configuración adecuada. Puedes usar las siguientes credenciales para iniciar sesión:</p>
                <ul>
                    <li><strong>Usuario:</strong> hal</li>
                    <li><strong>Contraseña:</strong> password</li>
                </ul>
                <p class="mt-3">Por favor, inicia sesión para continuar.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
