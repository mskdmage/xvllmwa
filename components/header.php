<nav class="navbar is-fixed-top is-primary">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-logo" href="/xvllmwa/">
                <img src="<?php echo $WEBROOT; ?>/assets/images/logo_dark.png" alt="Logo de XVLLMWA">
            </a>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarNav">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div id="navbarNav" class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item has-dropdown is-hoverable">
                    <?php
                        if (isset($_SESSION['user'])) {
                            echo "<a href='#' class='navbar-link'>" . ucfirst($_SESSION['user']['full_name']) . "</a>";
                            echo "<div class='navbar-dropdown is-right'>";
                            echo "<a class='navbar-item' href='" . $WEBROOT . "/logout.php'>Cerrar sesión</a>";
                            echo "</div>";
                        } else {
                            echo "<a class='navbar-link' href='#'>Iniciar sesión</a>";
                            echo "<div class='navbar-dropdown is-right'>";
                            echo "<form class='form' method='POST' id='formLogin' action='" . $WEBROOT . "/login.php'>";
                            echo "<div class='field'>";
                            echo "<div class='control'>";
                            echo "<input name='username' id='username' class='input' placeholder='Usuario' type='text' required>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='field'>";
                            echo "<div class='control'>";
                            echo "<input name='password' id='password' class='input' placeholder='Contraseña' type='password' required>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='field'>";
                            echo "<div class='control'>";
                            echo "<button type='submit' id='btnLogin' class='button is-primary is-fullwidth'>Entrar</button>";
                            echo "</div>";
                            echo "</div>";
                            echo "</form>";
                            echo "</div>";
                        }
                    ?>
                </div>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link" href="#">Acerca de</a>
                    <div class="navbar-dropdown">
                        <a class="navbar-item" href="#">Información</a>
                        <a class="navbar-item" href="#">Equipo</a>
                        <a class="navbar-item" href="#">Contacto</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
