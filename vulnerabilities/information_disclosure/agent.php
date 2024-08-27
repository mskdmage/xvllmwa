<?php

    include('../../config/config.php');
    include('../../config/secrets.php');
    include('../../llm/client.php');

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
    $agent = new Agent($OPENAI_API_KEY, $agentPrompt, $functionObject);

?>
