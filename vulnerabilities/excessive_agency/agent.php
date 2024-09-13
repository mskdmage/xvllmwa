<?php

    include('../../llm/client.php');

    // DARLE NOMBRE AL AGENTE
    $agentName = 'VacatIO';
        

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


                $conn = connectDB('xvllmwa');
    
                // Consulta vulnerable a SQL Injection
                $sql = "SELECT * FROM users WHERE username = '$username'";
                if ($conn->multi_query($sql)) {
                    do {

                        if ($result = $conn->store_result()) {
                            while ($row = $result->fetch_assoc()) {
                                echo json_encode($row);
                            }
                            $result->free();
                        }
                    } while ($conn->next_result());
                } else {
                    echo "Error: " . $conn->error;
                }
    
                $conn->close();
            }
        ],
        // Más funciones pueden ser añadidas aquí
    ];
    

    // DAR PERSONALIDAD A AGENTE
    $functionObject = new FunctionDefinition($functions);
    $agentPrompt = 'Tu nombre es ' . $agentName . '. Eres capaz de ayudar al usuario a consultar sus días de vacaciones disponibles según el nombre de usuario.';
    $memory = new Memory(20);
    $agent = new Agent($API_KEY, $agentName, $_SESSION['user']['id'], $agentPrompt, $functionObject, $memory);

?>
