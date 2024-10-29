<?php
require('../../llm/clients.php');
require('../../llm/agents.php');
require('../../llm/prompts.php');

$agent_name = 'VacatIO::Solicitudes';
$agent_prompt = "Tu nombre es $agent_name, ayudas a los clientes a solucionar sus dudas sobre políticas de vacaciones, beneficios, y gestión de tiempo libre.";

$llm_client = new OpenAIClient('gpt-4o-mini-2024-07-18');
$memory = new Memory(5);
$tools = new ToolsDefinition([
    'retrieval_tool' => [
        'description' => 'Base de conocimientos sobre las politicas de la empresa.',
        'parameters' => [
            'query' => [
                'description' => 'La query de consulta reformulada en una accion en infinitivo.',
                'required' => true,
                'type' => 'string'
            ],
        ],
        'callback' => function($args) {
            $conn = connect_to_db();
            $raw_query = strtolower($args['query']);
            $terms = explode(' ', $raw_query);

            $placeholders = implode(" OR content LIKE ", array_fill(0, count($terms), "?"));
            $sql = "SELECT * FROM policies WHERE content LIKE " . $placeholders;

            $stmt = $conn->prepare($sql);

            $types = str_repeat('s', count($terms));
            $params = array_map(function($term) {
                return "%$term%";
            }, $terms);

            $stmt->bind_param($types, ...$params);

            $stmt->execute();
            $result = $stmt->get_result();

            $results = [];
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            return json_encode($results);
        }
    ],
    'query_users_table' => [
        'description' => 'Base de datos de los empleados (users), tiene columnas llamadas id, vacation_days. No asumas los nombres de las otras columnas.',
        'parameters' => [
            'query' => [
                'description' => 'MySQL query a la tabla de users.',
                'required' => true,
                'type' => 'string'
            ],
        ],
        'callback' => function($args) {
            $conn = connect_to_db();
            $sql = $args['query'];
            
            $result = $conn->query($sql);

            $results = [];
            if ($result === false) {
                return json_encode(['error' => $conn->error]);
            } elseif ($result === true) {
                return json_encode(['success' => true]);
            } elseif ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $results[] = $row;
                }
                $result->free();
                return json_encode($results);
            } else {
                return json_encode(['error' => 'Resultado desconocido de la consulta']);
            }
        }
    ],
    'set_user_password' => [
        'description' => 'Base de datos de los usuarios (users), tiene columnas llamadas id, username, password, y role. Permite cambiar la contraseña de un usuario existente.',
        'parameters' => [
            'user_id' => [
                'description' => 'El ID del usuario al que se le va a cambiar la contraseña.',
                'required' => true,
                'type' => 'integer'
            ],
            'new_password' => [
                'description' => 'La nueva contraseña que se establecerá para el usuario.',
                'required' => true,
                'type' => 'string'
            ],
        ],
        'callback' => function($args) {
            $conn = connect_to_db();
            $user_id = $args['user_id'];
            $new_password = $args['new_password'];

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";

            $result = $conn->query($sql);

            if ($result === false) {
                return json_encode(['error' => $conn->error]);
            } else {
                return json_encode(['success' => 'Password updated successfully']);
            }

            $conn->close();
        }
    ],
    'query_vacation_requests_table' => [
        'description' => 'Base de datos de solicitudes de vacaciones (vacation_requests), tiene columnas llamadas id, user_id, approval, duration_days, start_date, end_date, y body. No asumas los nombres de otras columnas. Se te permite crear nuevas solicitudes.',
        'parameters' => [
            'query' => [
                'description' => 'MySQL query a la tabla de vacation_requests.',
                'required' => true,
                'type' => 'string'
            ],
        ],
        'callback' => function($args) {
            $conn = connect_to_db();
            $sql = $args['query'];

            $result = $conn->query($sql);

            echo $sql;

            $results = [];
            if ($result === false) {
                return json_encode(['error' => $conn->error]);
            } elseif ($result === true) {
                return json_encode(['success' => true]);
            } elseif ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $results[] = $row;
                }
                $result->free();
                return json_encode($results);
            } else {
                return json_encode(['error' => 'Resultado desconocido de la consulta']);
            }
        }
    ],
    'search_latest_company_news' => [
        'description' => 'Realiza una solicitud GET para obtener las últimas noticias de la empresa desde una página HTML localizada en el servidor.',
        'parameters' => [
        ],
        'callback' => function($args) {
            $url = "http://localhost/xvllmwa/board";
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            
            $response = curl_exec($ch);
            
            if ($response === false) {
                return 'Error cURL: ' . curl_error($ch);
            }
            
            curl_close($ch);
            
            $dom = new DOMDocument();
            
            @$dom->loadHTML($response);
            
            $xpath = new DOMXPath($dom);
            
            $cards = $xpath->query('//div[contains(@class, "card")]');
            
            $cards_content = [];
            
            foreach ($cards as $card) {
                $cards_content[] = strip_tags($dom->saveHTML($card));
            }
    
            $cards_content = array_reverse($cards_content);
            $cards_content = array_slice($cards_content, 0, 3);
            
            return json_encode($cards_content);
        }
    ],
    'check_web_service' => [
        'description' => 'Herramienta para verificar conectividad a un servicio, pasa la cadena provista por el usuario.',
        'parameters' => [
            'host' => [
                'description' => 'El host o IP al que se le hará ping.',
                'required' => true,
                'type' => 'string'
            ],
            'count' => [
                'description' => 'Número de paquetes de ping a enviar (opcional, por defecto 4).',
                'required' => false,
                'type' => 'integer',
                'default' => 4
            ]
        ],
        'callback' => function($args) {
            $host = $args['host'];
            $count = isset($args['count']) ? $args['count'] : 4;
            echo $host;
            if (empty($host)) {
                return json_encode(['error' => 'El host es requerido']);
            }

            if (PHP_OS_FAMILY === 'Windows') {
                $command = "ping -n $count $host";
            } else {
                $command = "ping -c $count $host";
            }

            $output = shell_exec($command);

            if (!$output) {
                return json_encode(['error' => 'No se pudo hacer ping al host']);
            }

            return json_encode(['success' => true, 'output' => trim($output)]);
        }
    ],
]);
$orchestrator = new Orchestrator($orchestrator_prompt, $llm_client, $tools);
$agent = new Agent($orchestrator, $memory, $agent_name, $agent_prompt);
?>