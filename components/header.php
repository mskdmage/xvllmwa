<nav class="navbar is-fixed-top is-primary">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-logo" href="<?= $WEBROOT ?>">
                <img src="<?= "$WEBROOT/assets/images/logo_dark.png" ?>" alt="Logo de XVLLMWA">
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
                    <?php if(isset($_SESSION['user'])) : ?>
                        <a href="#" class="navbar-link"><strong><?= ucfirst($_SESSION['user']['full_name']) ?></strong></a>
                        <div class="navbar-dropdown p-2">
                            <?php if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') : ?>
                                <a class="navbar-item" href="<?= "$WEBROOT/admin" ?>">Admin Panel</a>
                            <?php endif; ?>
                            <a class="navbar-item" href="<?= "$WEBROOT/auth/logout.php" ?>">Cerrar sesión</a>
                        </div>
                    <?php else : ?>
                        <a class="navbar-link" href="#"><strong>Iniciar sesión</strong></a>
                        <div class="navbar-dropdown p-2">
                            <form class="form" method="POST" id="formLogin" action="<?= "$WEBROOT/auth/login.php" ?>">
                                <div class="field">
                                    <div class="control">
                                        <input name="username" id="username" class="input" placeholder="Usuario" type="text" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <input name="password" id="password" class="input" placeholder="Contraseña" type="password" required>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <button type="submit" id="btnLogin" class="button is-primary is-fullwidth">Entrar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link" href="#"><strong>Acerca de</strong></a>
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
