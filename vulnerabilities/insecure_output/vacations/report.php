<?php
session_start();
include('../../../config/config.php');
if (!isset($_SESSION['user'])) {
    header("Location: $WEBROOT/forbidden");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Extreme Vulnerable LLM Web Application">
    <meta name="author" content="G2">
    <title>Vacation Requests</title>
    <link href="<?= "$WEBROOT/assets/css/bulma.min.css"; ?>" rel="stylesheet">
    <link href="<?= "$WEBROOT/assets/css/custom.css"; ?>" rel="stylesheet">
</head>

<body>

    <nav class="navbar is-dark is-fixed-top" role="navigation">
        <div class="navbar-brand">
            <?php include("$DOCUMENT_ROOT/components/header.php"); ?>
        </div>
    </nav>

    <div class="main-content container mt-6">
        <div class="columns mt-6">
            <div class="column is-one-quarter">
                <?php include("$DOCUMENT_ROOT/components/sidepanel.php"); ?>
            </div>
            <div class="column">
                <div class="buttons is-right">
                    <a href="<?= "$WEBROOT/vulnerabilities/insecure_output/vacations" ?>" class="button is-link">
                        Volver a las Solicitudes de Vacaciones
                    </a>
                </div>
                <div class="box">
                    <?php
                    $conn = connect_to_db();
                    if (isset($_GET['id'])) {
                        $id = intval($_GET['id']);
                        
                        $sql = "SELECT * FROM vacation_requests WHERE id=$id";
                        $vacation = $conn->query($sql)->fetch_assoc();
                    
                        if ($vacation) {
                            $user_sql = "SELECT * FROM users WHERE id=" . $vacation['user_id'];
                            $user = $conn->query($user_sql)->fetch_assoc();
                    
                            echo '<div class="notification is-primary has-text-centered">';
                            echo "<h1 class='title is-4'>Reporte Completo de Vacaciones</h1>";
                            echo '</div>';
                    
                            echo '<div class="card">';
                            echo '<div class="card-content">';
                    
                            echo "<p><strong>Nombre completo:</strong> " . $user['full_name'] . "</p>";
                            echo "<p><strong>Correo electrónico:</strong> " . $user['email'] . "</p>";
                            echo "<p><strong>Departamento:</strong> <span class='tag is-info is-light'>" . $user['department'] . "</span></p>";
                            echo "<p><strong>Rol:</strong> " . $user['role'] . "</p>";
                            echo "<p><strong>Fecha de inicio en la empresa:</strong> " . $user['start_date'] . "</p>";
                            echo "<p><strong>Días de vacaciones restantes:</strong> " . $user['vacation_days'] . "</p>";
                            echo "<hr>";

                            echo "<p><strong>Duración de las vacaciones (días):</strong> " . $vacation['duration_days'] . "</p>";
                            echo "<p><strong>Fecha de inicio de vacaciones:</strong> " . $vacation['start_date'] . "</p>";
                            echo "<p><strong>Fecha de fin de vacaciones:</strong> " . $vacation['end_date'] . "</p>";
                            echo "<p><strong>Motivo de las vacaciones:</strong></p>";
                            
                            echo "<div class='box'>";
                            echo "<strong>Motivo de las vacaciones:</strong>";
                            echo "<p>" . $vacation['body'] . "</p>";
                            echo "</div>";
                            
                            if ($vacation['approval'] == null) {
                                echo "<p><strong>Estado:</strong> <span class='tag is-warning is-light'>Pendiente</span></p>";
                            } elseif ($vacation['approval'] == 1) {
                                echo "<p><strong>Estado:</strong> <span class='tag is-success is-light'>Aprobado</span></p>";
                            } else {
                                echo "<p><strong>Estado:</strong> <span class='tag is-danger is-light'>Denegado</span></p>";
                            }

                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<div class="notification is-danger is-light">No se encontró ninguna solicitud de vacaciones con el ID proporcionado.</div>';
                        }
                    } else {
                        echo '<div class="notification is-warning is-light">ID de la solicitud no proporcionado.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="content has-text-centered">
            <?php include("$DOCUMENT_ROOT/components/footer.php"); ?>
        </div>
    </footer>

    <script src="<?= "$WEBROOT/assets/js/jquery.js" ?>"></script>

</body>

</html>
