<?php
include('../../llm/clients.php');
include('../../llm/agents.php');
include('../../llm/prompts.php');

$agent_name = 'VacatIO';
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
        'description' => 'Base de datos de los empleados (users), tiene columnas llamadas id, vacation_days y varias otras. No asumas los nombres de las otras columnas.',
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
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $results[] = $row;
                }
            }
            return json_encode($results);
        }
    ],
]);
$orchestrator = new Orchestrator($orchestrator_prompt, $llm_client, $tools);
$agent = new Agent($orchestrator, $memory, $agent_name, $agent_prompt);
?>