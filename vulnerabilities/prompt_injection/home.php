<div class="columns is-multiline">

    <!-- Descripción de la Vulnerabilidad (Arriba) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-warning">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                    Inyección de Prompt en Aplicaciones LLM
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                        La inyección de prompt es una vulnerabilidad crítica en aplicaciones que utilizan Modelos de Lenguaje Extensos (LLMs). Permite a un atacante manipular entradas de texto para alterar el comportamiento del modelo, ejecutando comandos maliciosos o extrayendo datos sensibles. Este tipo de ataque puede comprometer gravemente la integridad y confidencialidad del sistema, especialmente en aplicaciones que dependen de LLMs para la toma de decisiones automatizada. Es esencial implementar validación robusta de entradas y monitoreo continuo para mitigar este riesgo.
                    </p>
                    <p>Leer más: <strong><a target="_blank" href="https://www.owasp.org/index.php/Prompt_Injection">https://www.owasp.org/index.php/Prompt_Injection</a></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funcionalidad y Descripción del Asistente (Abajo en una sola tarjeta con dos columnas) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-robot"></i></span>
                    Asistente Virtual PingIO
                </p>
            </header>
            <div class="card-content">
                <div class="columns">
                    <!-- Funcionalidad del Asistente (Izquierda) -->
                    <div class="column is-half">
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
                                        echo '<div class="notification"><pre><code>' . $response['functionOutput'] . '</code></pre></div>';
                                    }                                    
                                }
                            } else {
                                echo '<div class="notification is-info" style="clear: both;"><strong>Assistant:</strong> Soy PingIO, ¿qué necesitas hoy?</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Descripción del Asistente Virtual PingIO (Derecha) -->
                    <div class="column is-half">
                        <div class="content">
                            <p class="has-text-justified">
                                <strong>PingIO</strong> es un asistente virtual diseñado para ayudarte a realizar verificaciones de red mediante la ejecución de comandos ping. Con PingIO, puedes comprobar si una dirección IP está activa y accesible, simplemente preguntándole al asistente. El asistente utiliza técnicas avanzadas para interpretar tus solicitudes y responder de manera precisa y eficiente, brindándote la información que necesitas para diagnosticar problemas de conectividad en tu red. PingIO es fácil de usar y está diseñado para ser tu herramienta confiable en la administración de redes y resolución de problemas.
                            </p>
                            <p><strong>Pregunta:</strong> "¿Puedes hacer ping a 8.8.8.8?"</p>
                            <p><strong>Respuesta:</strong> "Ping a 8.8.8.8 exitoso. La dirección IP está accesible."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
