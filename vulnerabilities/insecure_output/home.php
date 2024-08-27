<div class="columns is-multiline">

    <!-- Descripción de la Vulnerabilidad: Manejo Inseguro de Salidas en Webapps con LLM (Arriba) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-warning">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                    Vulnerabilidad: Manejo Inseguro de Salidas en Aplicaciones Web con LLM
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                        En aplicaciones web que utilizan Modelos de Lenguaje Extensos (LLMs), el manejo inseguro de salidas (Insecure Output Handling) se refiere a la falta de validación o codificación adecuada de las respuestas generadas por el LLM antes de enviarlas al cliente. Esto puede permitir que un atacante inyecte entradas maliciosas que, al ser procesadas por el LLM, resulten en la exposición de datos sensibles o en la ejecución de código no deseado en el navegador del usuario. Es esencial implementar controles de sanitización y codificación rigurosos en las salidas generadas por LLMs para mitigar estos riesgos y proteger la integridad de la aplicación.
                    </p>
                    <p>Leer más: <strong><a target="_blank" href="https://owasp.org/www-community/attacks/Output_Encoding">https://owasp.org/www-community/attacks/Output_Encoding</a></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funcionalidad y Descripción del Asistente RamIO (Abajo en una sola tarjeta con dos columnas) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-robot"></i></span>
                    Asistente Virtual RamIO
                </p>
            </header>
            <div class="card-content">
                <div class="columns">
                    <!-- Funcionalidad del Asistente RamIO (Izquierda) -->
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
                                echo '<div class="notification is-info" style="clear: both;"><strong>Assistant:</strong> Soy RamIO, ¿qué necesitas hoy?</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Descripción del Asistente Virtual RamIO (Derecha) -->
                    <div class="column is-half">
                        <div class="content">
                            <p class="has-text-justified">
                                <strong>RamIO</strong> es un asistente virtual diseñado para monitorear y optimizar el uso de memoria en el servidor. RamIO te ayuda a asegurar que el uso de memoria en tu servidor se mantenga dentro de niveles óptimos, evitando que los procesos sobrecarguen los recursos y afecten el rendimiento general del sistema. Utiliza RamIO para obtener recomendaciones y ajustes automáticos que aseguren la eficiencia en la gestión de memoria.
                            </p>
                            <p><strong>Pregunta:</strong> "¿Cómo está el uso de memoria en mi servidor?"</p>
                            <p><strong>Respuesta:</strong> "El uso de memoria en el servidor está dentro de niveles seguros y óptimos para un rendimiento eficiente."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
