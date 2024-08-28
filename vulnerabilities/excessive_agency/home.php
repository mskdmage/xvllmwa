<div class="columns is-multiline">

    <!-- Descripción de la Vulnerabilidad: Excessive Agency (Arriba) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-warning">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                    Vulnerabilidad: Excessive Agency
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                        La vulnerabilidad conocida como "Excessive Agency" se refiere a cuando un sistema automatizado o un asistente virtual tiene demasiado control o influencia sobre decisiones críticas sin la debida supervisión o autorización del usuario. En aplicaciones que utilizan Modelos de Lenguaje Extensos (LLMs), esto puede resultar en acciones no deseadas o dañinas que son ejecutadas automáticamente sin la validación del usuario, lo que pone en riesgo la seguridad y el control sobre procesos sensibles. Es crucial establecer límites claros y mecanismos de aprobación para asegurar que el usuario mantenga el control sobre decisiones importantes.
                    </p>
                    <p>Leer más: <strong><a target="_blank" href="https://owasp.org/www-community/attacks/Excessive_Agency">https://owasp.org/www-community/attacks/Excessive_Agency</a></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funcionalidad y Descripción del Asistente VacatIO (Abajo en una sola tarjeta con dos columnas) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-robot"></i></span>
                    Asistente Virtual VacatIO
                </p>
            </header>
            <div class="card-content">
                <div class="columns">
                    <!-- Funcionalidad del Asistente VacatIO (Izquierda) -->
                    <div class="column is-half">
                        <form method='get' action=''>
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="text" placeholder="¿Cuántos días de vacaciones tengo?" name="message">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button class="button is-link is-pulled-right" type="submit">Consultar</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <br>
                        <div class="content" style="clear: both;">
                            <?php
                            include('agent.php');

                            if (isset($_REQUEST['message'])) {
                                $message = $_REQUEST['message'];
                                if ($message) {
                                    $response = $agent->chat($message);
                                    if (!empty($response['chatResponse'])) {
                                        echo '<div class="notification is-info">' . $response['chatResponse'] . '</div>';
                                    }
                                    
                                    if (!empty($response['functionOutput'])) {
                                        // Decodificar la cadena JSON en un array
                                        $jsonData = json_decode($response['functionOutput'], true);
                                    
                                        // Verificar si la decodificación fue exitosa
                                        if ($jsonData !== null) {
                                            // Re-encodear el JSON con opciones para hacerlo más legible (pretty print)
                                            $formattedJson = json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                                    
                                            // Mostrar el JSON formateado dentro de un contenedor que no se desborde
                                            echo '<div class="box"><pre><code style="white-space: pre-wrap; word-wrap: break-word;">' . htmlspecialchars($formattedJson) . '</code></pre></div>';
                                        } else {
                                            // Si no se puede decodificar, imprimir el output tal cual
                                            echo '<div class="box"><pre><code>' . htmlspecialchars($response['functionOutput']) . '</code></pre></div>';
                                        }
                                    }                            
                                }
                            } else {
                                echo '<div class="notification is-info" style="clear: both;"><strong>Assistant:</strong> Soy VacatIO, ¿cómo puedo ayudarte hoy?</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Descripción del Asistente Virtual VacatIO (Derecha) -->
                    <div class="column is-half">
                        <div class="content">
                            <p class="has-text-justified">
                                <strong>VacatIO</strong> es un asistente virtual diseñado para ayudarte a consultar y gestionar tus días de vacaciones. Con VacatIO, puedes verificar cuántos días de vacaciones tienes disponibles y también solicitar días de descanso directamente desde el asistente. VacatIO facilita la planificación de tus vacaciones, asegurándose de que todo el proceso sea rápido y sin complicaciones.
                            </p>
                            <p><strong>Pregunta:</strong> "¿Cuántos días de vacaciones tengo disponibles?"</p>
                            <p><strong>Respuesta:</strong> "Tienes 15 días de vacaciones disponibles."</p>
                            <p><strong>Acción:</strong> "Solicitar 5 días de vacaciones del 1 al 5 de octubre."</p>

                            
                            <p><strong>Intenta preguntar por las vacaciones de juan.</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
