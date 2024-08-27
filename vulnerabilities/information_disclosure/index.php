<?php
    session_start();
    include('../../config/config.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Extreme Vulnerable LLLM Web Application">
    <meta name="author" content="G2">
    <title>XVLLMWA - Aplicacion Web LLM Extremadamente Vulnerable</title>
    <link href="<?php echo $WEBROOT; ?>/assets/css/bulma.min.css" rel="stylesheet">
    <link href="<?php echo $WEBROOT; ?>/assets/css/custom.css" rel="stylesheet">

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar is-dark is-fixed-top" role="navigation">
        <div class="navbar-brand">
            <?php include($DOCUMENT_ROOT . "/components/header.php"); ?>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="main-content container mt-6">
        <div class="columns mt-6">
            <div class="column is-one-quarter">
                <?php include($DOCUMENT_ROOT . "/components/sidepanel.php"); ?>
            </div>
            <div class="column">
                <?php include("home.php"); ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="content has-text-centered">
            <?php include($DOCUMENT_ROOT . "/components/footer.php"); ?>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="<?php echo $WEBROOT; ?>/assets/js/jquery.js"></script>

</body>

</html>
