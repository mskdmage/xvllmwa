<?php
session_start();
require('../config/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Extreme Vulnerable LLLM Web Application">
    <meta name="author" content="G2">
    <title>XVLLMWA - Equipo</title>
    <link href="<?= "$WEBROOT/assets/css/bulma.min.css"; ?>" rel="stylesheet">
    <link href="<?= "$WEBROOT/assets/css/custom.css"; ?>" rel="stylesheet">
</head>

<body>

    <nav class="navbar is-dark is-fixed-top" role="navigation">
        <div class="navbar-brand">
            <?php require("$DOCUMENT_ROOT/components/header.php"); ?>
        </div>
    </nav>

    <div class="main-content container mt-6">
        <div class="columns mt-6">
            <div class="column is-one-quarter">
                <?php require("$DOCUMENT_ROOT/components/sidepanel.php"); ?>
            </div>
            <div class="column">

               <div class="card">
                    <header class="card-header">
                        <p class="card-header-title is-centered">
                            El Equipo
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content has-text-left">
                            <div class="is-flex is-justify-content-center mt-4">
                                <figure class="image" style="width: 12rem;">
                                    <img src="<?= "$WEBROOT/assets/images/threat_hunting.png"; ?>" alt="hound">
                                </figure>
                            </div>
                            <div class="is-flex is-justify-content-center m-4">
                                <p>
                                    Este proyecto fue realizado por los estudiantes de la Maestría en Ciberseguridad:
                                    Alberto B., Marco A., Javier H., y David M., de la Universidad Internacional del Ecuador.
                                </p>
                            </div>
                            <div class="is-flex is-justify-content-center m-4">
                                <p>
                                    Agradecemos también a los profesores y al personal académico de la UIDE por su apoyo constante
                                    y compromiso en nuestra formación profesional.
                                </p>
                            </div>
                            <div class="is-flex is-justify-content-center m-4">
                                <figure class="image" style="width: 4rem;">
                                    <img src="<?= "$WEBROOT/assets/images/logo.gif"; ?>" alt="logo">
                                </figure>
                                <figure class="image" style="width: 7rem;">
                                    <img src="<?= "$WEBROOT/assets/images/uide.jpg"; ?>" alt="Logo de la UIDE">
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="content has-text-centered">
            <?php require("$DOCUMENT_ROOT/components/footer.php"); ?>
        </div>
    </footer>

    <script src="<?= "$WEBROOT/assets/js/jquery.js" ?>"></script>

</body>

</html>