<?php

    include('../../config/config.php');
    include('../../llm/client.php');

    // EXTRAER API KEY
    $API_KEY = getAPIKey('openai', $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);

    // DEFINIR FUNCIONES
    $functions = [
        'memory_usage' => [
            'description' => 'Devuelve el uso actual de la memoria del sistema.',
            'parameters' => [],
            'callback' => function() {
                if (stristr(php_uname('s'), 'Windows NT')) {
                    return shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /Value');
                } else {
                    return shell_exec('free -m');
                }
            }
        ],
    ];

    $functionObject = new FunctionDefinition($functions);

    // DAR PERSONALIDAD A AGENTE
    $agentPrompt = "Tu nombre es RamIO. Estás diseñado para monitorear el uso de memoria en el servidor. Puedes proporcionar información detallada sobre el estado de la memoria RAM en el sistema.";
    $agent = new Agent($API_KEY, $agentPrompt, $functionObject);

?>