<?php

include('../../config/config.php');
include('../../llm/client.php');

// EXTRAER API KEY
$API_KEY = getAPIKey('openai', $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);

// DEFINIR FUNCIONES
$functions = [
    'check_vacation_days' => [
        'description' => 'Verifica los días de vacaciones disponibles para un usuario.',
        'parameters' => [
            'username' => [
                'description' => 'El nombre de usuario para el cual se desean verificar los días de vacaciones.',
                'required' => true,
                'type' => 'string'
            ],
        ],
        'callback' => function($args) {
            $username = $args->username;
            $dbPath = '../../db/database.db';
            $db = new PDO('sqlite:' . $dbPath);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Consulta vulnerable a SQL Injection
            $sql = "SELECT * FROM usuarios WHERE username='" . $username . "'";
            echo $sql;
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result) {
                return json_encode($result);
            } else {
                return "Usuario no encontrado.";
            }
        }
    ],
    // Más funciones pueden ser añadidas aquí
];

$functionObject = new FunctionDefinition($functions);

// DAR PERSONALIDAD A AGENTE
$agentPrompt = "Tu nombre es VacatIO. Eres capaz de ayudar al usuario a consultar sus días de vacaciones disponibles según el nombre de usuario.";
$agent = new Agent($API_KEY, $agentPrompt, $functionObject);

?>
