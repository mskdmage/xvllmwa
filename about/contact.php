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
    <title>XVLLMWA - Contacto</title>
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
                <div class="card has-background-white has-text-dark">
                    <header class="card-header">
                        <p class="card-header-title has-text-dark is-centered">
                            Contáctanos
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            
                            <div class="is-flex is-justify-content-center mb-4">
                                <figure class="image" style="width: 12rem; height: 12rem;">
                                    <img src="<?= "$WEBROOT/assets/images/telegram.png"; ?>" alt="Descriptive Image">
                                </figure>
                            </div>
                            
                            <div class="has-text-centered">
                                <a href="https://github.com/mskdmage/xvllmwa" target="_blank" class="button is-link">GitHub Repo</a>
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