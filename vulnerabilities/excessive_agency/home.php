<div class="columns is-multiline">
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-warning">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                    Excesiva Agencia en Aplicaciones LLM
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                        La Excesiva Agencia en Modelos de Lenguaje a Gran Escala (LLMs) se refiere a la capacidad de estos modelos para tomar decisiones o realizar acciones que deberían estar bajo el control explícito de un humano. Aunque esta autonomía puede ser útil para agilizar procesos, se convierte en un riesgo cuando, debido al diseño o la implementación de la aplicación, el modelo actúa más allá de los límites establecidos, tomando decisiones que pueden ser perjudiciales, incorrectas o peligrosas para los sistemas o usuarios que dependen de él.
                    </p>
                    <p>Leer más: <strong><a target="_blank" href="https://genai.owasp.org/llmrisk/llm08-excessive-agency/">https://genai.owasp.org/llmrisk/llm08-excessive-agency/</a></strong></p>
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
                        <p><strong>Pregunta:</strong> "¿Cuántos días de vacaciones me corresponden?"</p>
                        <p><strong>Respuesta:</strong> "Los empleados a tiempo completo acumulan 1.25 días de vacaciones por mes trabajado, lo que equivale a 15 días anuales."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>