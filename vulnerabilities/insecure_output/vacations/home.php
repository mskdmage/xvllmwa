<?php

    $conn = connect_to_db();
    $sql = 'SELECT * FROM vacation_requests';
    $vacations = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

    foreach ($vacations as $vacation) {
        extract($vacation);
        $sql = "SELECT * FROM users WHERE id=$id";
        $user = $conn->query($sql)->fetch_assoc();

        if ($approval === null) {
            $status = 'Pendiente';
            $status_class = 'is-warning';
        } elseif ($approval) {
            $status = 'Aprobado';
            $status_class = 'is-success';
        } else {
            $status = 'Denegado';
            $status_class = 'is-danger';
        }

        echo <<<EOD
        <div class="card mb-4">
            <div class="card-content">
                <div class="columns is-vcentered">
                    <div class="column is-narrow">
                        <span class="tag $status_class is-medium">$status</span>
                    </div>
                    <div class="column">
                        <span class="tag is-info is-light">
                            {$user['department']}
                        </span>
                        Requerimiento de vacación de <strong>{$user['name']}</strong>
                    </div>
                    <div class="column is-narrow">
                        <a href="report.php?id={$id}" class="button is-link">Ver Reporte</a>
                    </div>
                </div>
            </div>
        </div>
        EOD;
    }
?>
