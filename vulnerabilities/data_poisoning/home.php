<div class="columns is-multiline">

    <!-- Descripción de la Vulnerabilidad: Envenenamiento de Datos de Entrenamiento y Consulta (Arriba) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header has-background-warning">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-exclamation-triangle"></i></span>
                    Vulnerabilidad: Envenenamiento de Datos de Entrenamiento y Consulta (Training Data Poisoning)
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <p class="has-text-justified">
                        El envenenamiento de datos de entrenamiento y consulta en aplicaciones que utilizan Modelos de Lenguaje Extensos (LLMs) se refiere a la manipulación maliciosa de los datos que se utilizan para entrenar o consultar el modelo. Este ataque puede introducir sesgos, errores o comportamientos no deseados en el modelo, comprometiendo su integridad y fiabilidad. Los atacantes pueden insertar datos intencionadamente diseñados para alterar el rendimiento del LLM o hacer que produzca resultados incorrectos. Es crucial implementar medidas de control de calidad en los datos y auditorías periódicas para detectar y mitigar este tipo de amenazas.
                    </p>
                    <p>Leer más: <strong><a target="_blank" href="https://owasp.org/www-community/attacks/Training_Data_Poisoning">https://owasp.org/www-community/attacks/Training_Data_Poisoning</a></strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Funcionalidad y Descripción del Asistente SpamCheckIO (Abajo en una sola tarjeta con dos columnas) -->
    <div class="column is-full">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="fas fa-robot"></i></span>
                    Asistente Virtual SpamCheckIO
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

                    <!-- Descripción del Asistente Virtual SpamCheckIO (Derecha) -->
                    <div class="column is-half">
                        <div class="content">
                            <p class="has-text-justified">
                                <strong>SpamCheckIO</strong> es un asistente virtual diseñado para ayudarte a verificar si un correo electrónico es spam analizando el título del correo. Con SpamCheckIO, puedes determinar rápidamente la probabilidad de que un correo sea no deseado o potencialmente peligroso, ayudándote a gestionar tu bandeja de entrada de manera más eficiente y segura.
                            </p>
                            <p><strong>Pregunta:</strong> "¿Este correo es spam?"</p>
                            <p><strong>Respuesta:</strong> "El correo tiene una alta probabilidad de ser spam." o "El correo parece legítimo."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
