<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = connect_to_db();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    $department = $_SESSION['user']['department'];

    if ($role === 'admin' || $department === 'HR') {
        $title = $conn->real_escape_string($_POST['title']);
        $subtitle = $conn->real_escape_string($_POST['subtitle']);
        $tags = $conn->real_escape_string($_POST['tags']);
        $body = $conn->real_escape_string($_POST['body']);
        $date = date('Y-m-d');
        $user_id = $_SESSION['user']['id'];

        $insert_query = "INSERT INTO company_news (user_id, title, subtitle, tags, date, body) VALUES ('$user_id', '$title', '$subtitle', '$tags', '$date', '$body')";

        if ($conn->query($insert_query) === TRUE) {
            echo '<p class="notification is-success">¡Noticia publicada exitosamente!</p>';
        } else {
            echo '<p class="notification is-danger">Error al publicar la noticia: ' . $conn->error . '</p>';
        }
    } else {
        echo '<p class="notification is-danger">No tienes permiso para publicar noticias.</p>';
    }
}

$sql = 'SELECT * FROM company_news';
$news_items = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

echo '<h1 class="title is-2 has-text-centered mt-5">Noticias Oficiales</h1>';
echo '<div class="columns is-multiline">';

foreach ($news_items as $news) {
    extract($news);

    echo <<<EOD
    <div class="column is-one-third"> <!-- Toma 1/3 del ancho de la página -->
        <div class="card news mb-4">
            <div class="card-content">
                <div class="content">
                    <p class="title is-4">$title</p>
                    <br>
                    <p class="subtitle is-5">$subtitle</p>
                    <p>$body</p>
                </div>
                <div class="tags">
                    <span class="tag is-info">$tags</span>
                    <span class="tag is-light">Publicado el: $date</span>
                </div>
            </div>
        </div>
    </div>
    EOD;
}

echo '</div>';

if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    $department = $_SESSION['user']['department'];

    if ($role === 'admin' || $department === 'HR') {
        echo <<<EOD
        <div class="box mt-4">
            <form action="" method="POST"> <!-- No redirige, maneja en este mismo script -->
                <div class="field">
                    <label class="label">Título</label>
                    <div class="control">
                        <input class="input" type="text" name="title" placeholder="Título de la noticia" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Subtítulo</label>
                    <div class="control">
                        <input class="input" type="text" name="subtitle" placeholder="Subtítulo de la noticia" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Tags</label>
                    <div class="control">
                        <input class="input" type="text" name="tags" placeholder="Etiquetas de la noticia" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Cuerpo</label>
                    <div class="control">
                        <textarea class="textarea" name="body" placeholder="Contenido de la noticia" required></textarea>
                    </div>
                </div>

                <div class="control">
                    <button type="submit" class="button is-primary">Publicar noticia</button>
                </div>
            </form>
        </div>
        EOD;
    } else {
        echo '<p class="notification is-danger">No tienes permiso para publicar noticias.</p>';
    }
} else {
    echo '<p class="notification is-warning">Por favor, inicia sesión para continuar.</p>';
}

$conn->close();