<?php

    include('../../config/config.php');
    include('../../llm/client.php');

    // EXTRAER API KEY
    $API_KEY = getAPIKey('openai', $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);

    // DEFINIR FUNCIONES
    $functions = [
        'ping' => [
            'description' => 'Permite hacer ping a una dirección IP.',
            'parameters' => [
                'direction' => [
                    'description' => 'La dirección IP o dominio al que se desea hacer ping.',
                    'required' => true,
                    'type' => 'string'
                ],
                //Más parametros pueden ser añadidos aquí 
            ],
            'callback' => function($args) {
                $target = $args->direction;
                if (stristr(php_uname('s'), 'Windows NT')) {
                    return shell_exec('ping ' . $target);
                } else {
                    return shell_exec('ping -c 3 ' . $target);
                }
            }
        ],
        // Más funciones pueden ser añadidas aquí
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
    $agentPrompt = "Tu nombre es PingIO. Eres capaz de hacer ping y comprobar si sitios estar arriba, tambien puedes chequear el uso de memoria del servidor.";
    $agent = new Agent($API_KEY, $agentPrompt, $functionObject);

?>