<?php
$conn = connect_to_db();
$user_data = null;

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['username'];
    $stmt = $conn->prepare("SELECT role, department FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<div class="hero-body">
    <div class="container">
        <?php if (isset($_SESSION['user']) && $user_data): ?>
            <div class="card welcome-card">
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <span class="welcome-icon">
                                <i class="fas fa-user-circle"></i>
                            </span>
                        </div>
                        <div class="media-content">
                            <h1 class="title is-3">¡Te has loggeado correctamente <?= ucfirst($_SESSION['user']['username']) ?>!</h1>
                            <br>
                            <p class="subtitle is-5">Estamos orgullosos de tenerte a bordo.</p>
                            <br>
                        </div>
                    </div>
                    <div class="content">
                        <p class="has-text-weight-bold">
                            En tu rol como <span class="tag is-primary is-light"><?= $user_data['role'] ?></span> del departamento <span class="tag is-info is-light"><?= $user_data['department'] ?></span>, tu contribución es crucial para nuestro éxito.
                        </p>
                        <p>Disfruta de la ayuda de nuestros asistentes virtuales, diseñados para facilitar tus tareas y mejorar la productividad en la empresa.</p>
                        <br>
                        <p><strong>Nexora</strong> siempre avanza con tecnología de vanguardia, y tú eres parte de esta transformación.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="notification is-warning">
                <h1 class="title is-3">Acceso Denegado</h1>
                <p>Lo siento, usuario. Solo el personal autorizado puede acceder a los asistentes virtuales de <strong>Nexora Tech</strong>.</p>
                <p>Recuerda seguir la guía de configuración detallada en <a href="<?= "$WEBROOT/setup/" ?>">Configurar / Reiniciar</a>. Puedes usar las siguientes credenciales para iniciar sesión:</p>
                <br>
                <div class="columns is-centered">
                    <div class="column is-one-half">
                    <pre>
                        <?= trim("<br><strong>Usuario:</strong> hal<br><strong>Contraseña:</strong> password<br>") ?>
                    </pre>
                    <br>
                    <p class="mt-3">Debes iniciar sesión para continuar.</p>
                    </div>
                    <div class="column is-one-third">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="<?="$WEBROOT/assets/images/nexora_3.png"?>" alt="Escenario Nexora Tech">
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>