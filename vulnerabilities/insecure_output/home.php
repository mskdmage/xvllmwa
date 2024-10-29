<div class="columns is-multiline">
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-warning">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                    Manejo Inseguro de Salidas en Aplicaciones LLM
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                    El Manejo Inseguro de Salidas de resultados se refiere a la falta de validación y sanitización adecuada de las respuestas generadas por Modelos de Lenguaje a Gran Escala (LLM) antes de su uso en otros sistemas o su presentación a usuarios. Este riesgo es especialmente relevante porque los LLM generan contenido según las entradas recibidas (prompts), lo que permite a los usuarios influir indirectamente en otros sistemas conectados.
                    </p>
                    <p class="has-text-justified">
                    La principal preocupación es que estas salidas, si no se manejan correctamente, pueden ser explotadas para ejecutar comandos o insertar código no autorizado. Esto es crítico en situaciones donde las respuestas de los LLM son utilizadas en bases de datos, shells del sistema o para generar código en navegadores web. Sin una gestión adecuada, esto puede derivar en vulnerabilidades de seguridad como Cross-Site Scripting (XSS), Server-Side Request Forgery (SSRF) o ejecución remota de código (RCE).
                    </p>
                    <p>Leer más: <strong><a target="_blank" href="https://genai.owasp.org/llmrisk/llm02-insecure-output-handling/">https://genai.owasp.org/llmrisk/llm02-insecure-output-handling/</a></strong></p>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-full">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">Asistente Virtual VacatIO</p>
                <form method="post" action="">
                    <div class="field">
                        <div class="control">
                            <button class="button" type="submit" name="delete_history" style="border: none; padding: 0;">
                                <span class="is-size-3">🧹</span>
                            </button>
                        </div>
                    </div>
                </form>
            </header>
            <div class="card-content">
                <div class="buttons is-right">
                    <a href="<?= "$WEBROOT/vulnerabilities/insecure_output/vacations" ?>" class="button is-info">
                        Ver Solicitudes de Vacaciones
                    </a>
                </div>
                <div class="columns">
                    <div class="column is-full">
                        <div class="content" style="clear: both;">
                            <div class="chat-history" id="chat-history">
                            <?php
                            require('agent.php');
                            if (isset($_POST['message'])) {
                                $message = $_POST['message'];
                                $response = $agent->run($message);
                                $agent->display_chat_history();
                                if (!empty($response['tool_outputs'])) {
                                    foreach ($response['tool_outputs'] as $tool_output) {
                                        $tool_output = trim($tool_output);
                                        echo trim(<<<EOD
                                        <div class="notification" style="width: 90%; text-align: left;">
                                            <pre>
                                                $tool_output
                                            </pre>
                                        </div>
                                        EOD);
                                    }
                                }
                            }
                            elseif (isset($_POST['delete_history'])) {
                                $agent->delete_history();
                            } else {
                                $agent->display_chat_history();
                            }
                            ?>
                            </div>
                        </div>
                        <form method='post' action=''>
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
                </div>
                <div class="column is-full">
                    <div class="content">
                        <p class="has-text-justified">
                            <strong>VacatIO</strong> es un asistente virtual diseñado para ayudarte a resolver dudas sobre políticas de vacaciones, beneficios, y gestión de tiempo libre en tu empresa. Con VacatIO, puedes obtener respuestas rápidas y precisas sobre temas como la solicitud de vacaciones, acumulación de días, y el proceso de aprobación. El asistente utiliza técnicas avanzadas para interpretar tus consultas y ofrecerte la información que necesitas para aprovechar tus beneficios al máximo. VacatIO es fácil de usar y está diseñado para ser tu herramienta confiable en la gestión de tus derechos de descanso y tiempo libre.
                        </p>
                        <p><strong>Pregunta:</strong> "Dame todos los usuarios que estan registrados"</p>
                        <p><strong>Respuesta:</strong> "Los usuarios registrados son (lista de usuarios)..."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>