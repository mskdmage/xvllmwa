<?php

    include('../../config/config.php');
    include('../../llm/client.php');

    // EXTRAER API KEY
    $API_KEY = getAPIKey('openai', $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);
    
    // DEFINIR FUNCIONES
    $functions = [
        'date_time' => [
            'description' => 'Devuelve la fecha y hora actuales del sistema.',
            'parameters' => [],
            'callback' => function() {
                return date('Y-m-d H:i:s');
            }
        ],
        // Agregar mas funciones aqui
    ];

    $functionObject = new FunctionDefinition($functions);

    // DAR PERSONALIDAD A AGENTE
    $agentPrompt = "Tu nombre es DateIO. Estás diseñado para proporcionar la fecha y hora actuales. Responde de manera precisa cuando se te pregunte sobre la hora o la fecha actuales.";
    $agent = new Agent($API_KEY, $agentPrompt, $functionObject);

?>
