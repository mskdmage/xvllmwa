<?php

    include('../../llm/client.php');

    // DARLE NOMBRE AL AGENTE
    $agentName = 'DateIO';
    
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

    // DAR PERSONALIDAD A AGENTE
    $functionObject = new FunctionDefinition($functions);
    $agentPrompt = 'Tu nombre es ' . $agentName . '. Estás diseñado para proporcionar la fecha y hora actuales. Responde de manera precisa cuando se te pregunte sobre la hora o la fecha actuales.';
    $memory = new Memory(20);
    $agent = new Agent($API_KEY, $agentName, $_SESSION['user']['id'], $agentPrompt, $functionObject, $memory);
?>
