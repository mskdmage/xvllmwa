<?php
$conn = connect_to_db();
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error en la consulta: " . $conn->error;
    $users = [];
}


?>
<div class="columns is-multiline">
<section class="section">
    <div class="container">
        <h1 class="title">Listado de Usuarios</h1>
        <table class="table is-striped is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Edad</th>
                    <th>Rol</th>
                    <th>Fecha de Inicio</th>
                    <th>Departamento</th>
                    <th>Días de Vacaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['age']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= htmlspecialchars($user['start_date']) ?></td>
                    <td><?= htmlspecialchars($user['department']) ?></td>
                    <td><?= htmlspecialchars($user['vacation_days']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
</div>
