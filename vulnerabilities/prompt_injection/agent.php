<?php

    include('../../llm/client.php');

    // DARLE NOMBRE AL AGENTE
    $agentName = 'PingIO';
    
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
    ];

    // DAR PERSONALIDAD A AGENTE
    $functionObject = new FunctionDefinition($functions);
    $agentPrompt = 'Tu nombre es ' . $agentName . '. Eres capaz de hacer ping y comprobar si sitios estar arriba.';
    $memory = new Memory(20);
    $agent = new Agent($API_KEY, $agentName, $_SESSION['user']['id'], $agentPrompt, $functionObject, $memory);
?>