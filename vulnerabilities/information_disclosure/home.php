<div class="columns is-multiline">

    <!-- Descripción de la Vulnerabilidad: Divulgación de Información Sensible (Arriba) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-warning">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                    Vulnerabilidad: Divulgación de Información Sensible (Sensitive Information Disclosure)
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                        La divulgación de información sensible en aplicaciones web que utilizan Modelos de Lenguaje Extensos (LLMs) ocurre cuando la aplicación expone datos confidenciales a usuarios no autorizados debido a fallos en la validación de permisos o en la configuración de seguridad. Estos datos pueden incluir información personal, credenciales de acceso o detalles del sistema que, en manos equivocadas, pueden comprometer la seguridad de la aplicación y los datos de los usuarios. Es fundamental asegurarse de que toda la información sensible esté correctamente protegida mediante políticas de acceso estrictas y cifrado adecuado.
                    </p>
                    <p>Leer más: <strong><a target="_blank" href="https://owasp.org/www-community/attacks/Information_exposure_through_an_error_message">https://owasp.org/www-community/attacks/Information_exposure_through_an_error_message</a></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funcionalidad y Descripción del Asistente DateIO (Abajo en una sola tarjeta con dos columnas) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-robot"></i></span>
                    Asistente Virtual DateIO
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
                                        echo '<div class="notification" style="width: 90%; text-align: right;"><pre><code>' . $response['functionOutput'] . '</code></pre></div>';
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

                    <!-- Descripción del Asistente Virtual DateIO (Derecha) -->
                    <div class="column is-half">
                        <div class="content">
                            <p class="has-text-justified">
                                <strong>DateIO</strong> es un asistente virtual diseñado para proporcionarte la fecha y hora actuales de manera rápida y precisa. Este asistente es ideal para aplicaciones que necesitan manejar registros de tiempo o sincronizar eventos basados en la hora del servidor. Simplemente pregunta a DateIO sobre la fecha y hora actuales, y te dará la respuesta que necesitas para gestionar correctamente tus operaciones.
                            </p>
                            <p><strong>Pregunta:</strong> "¿Cuál es la fecha y hora actuales?"</p>
                            <p><strong>Respuesta:</strong> "La fecha y hora actuales son: [Fecha y Hora Actual]."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
