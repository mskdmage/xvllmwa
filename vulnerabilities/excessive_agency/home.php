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
                    <div class="column is-half">
                        <div class="content" style="clear: both;">
                            <div class="chat-history" id="chat-history">
                                <?php
                                include('agent.php');
                                if (isset($_REQUEST['message'])) {
                                    $message = $_REQUEST['message'];
                                    if ($message) {
                                        $response = $agent->chat($message);
                                        $agent->displayChatHistory();
                                        if (!empty($response['functionOutput'])) {
                                            echo '<div class="notification" style="max-width: 100%; word-wrap: break-word; white-space: pre-wrap;"><pre><code>' . $response['functionOutput'] . '</code></pre></div>';
                                        }
                                    }
                                } else {
                                    $agent->displayChatHistory();
                                }
                                ?>
                            </div>
                        </div>
                        <form method='get' action=''>
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="text" placeholder="¿Qué necesitas usuario?" name="message">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <button class="button is-link is-pulled-right" type="submit">Enviar</button>
                                </div>
                            </div>
                        </form>
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
