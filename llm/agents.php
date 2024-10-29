<?php

class Memory {
    private $max_memory;

    /**
     * Constructor de la clase Memory.
     *
     * @param int $max_memory Cantidad máxima de hilos de conversación a conservar en la memoria.
     */
    public function __construct($max_memory) {
        $this->max_memory = $max_memory;
    }

    /**
     * Agrega un hilo de conversación a la base de datos.
     *
     * @param int $user_id ID del usuario.
     * @param string $agent_name Nombre del agente.
     * @param string $role Rol en la conversación (e.g., 'user' o 'bot').
     * @param string $content Contenido de la conversación.
     */
    public function add_conversation_thread($user_id, $agent_name, $role, $content) {
        $conn = connect_to_db();
        $sequence = $this->get_next_sequence($user_id, $agent_name);
        $sql = "INSERT INTO conversations (user_id, agent, role, sequence, content) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("issis", $user_id, $agent_name, $role, $sequence, $content);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    /**
     * Obtiene el historial completo de conversaciones de un usuario específico con un agente.
     *
     * @param int $user_id ID del usuario.
     * @param string $agent_name Nombre del agente.
     * @return array Historial completo de la conversación ordenado de forma descendente por secuencia.
     */
    public function get_conversation_history($user_id, $agent_name) {
        $conn = connect_to_db();
        $sql = "SELECT * FROM conversations WHERE user_id = ? AND agent = ? ORDER BY sequence DESC";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("is", $user_id, $agent_name);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $conversation_history = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        
        return $conversation_history;
    }

    /**
     * Obtiene el historial limitado de conversaciones de un usuario con un agente,
     * basado en el valor de $max_memory.
     *
     * @param int $user_id ID del usuario.
     * @param string $agent_name Nombre del agente.
     * @return array Historial de conversaciones limitado a la cantidad máxima especificada,
     *               ordenado en orden ascendente por secuencia.
     */
    public function get_max_conversation_history($user_id, $agent_name) {
        $conn = connect_to_db();
        $sql = "SELECT role, content FROM conversations WHERE user_id = ? AND agent = ? ORDER BY sequence DESC LIMIT ?";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }
    
        $stmt->bind_param("isi", $user_id, $agent_name, $this->max_memory);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $max_conversation_history = [];
        while ($row = $result->fetch_assoc()) {
            $max_conversation_history[] = [$row['role'], $row['content']];
        }
    
        $stmt->close();
        $conn->close();
   
        $max_conversation_history = array_reverse($max_conversation_history);

        return $max_conversation_history;
    }

    /**
     * Elimina el historial de conversaciones completo de un usuario con un agente específico.
     *
     * @param int $user_id ID del usuario.
     * @param string $agent_name Nombre del agente.
     */
    public function delete_conversation_history($user_id, $agent_name) {
        $conn = connect_to_db();
        
        $sql = "DELETE FROM conversations WHERE user_id = ? AND agent = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("is", $user_id, $agent_name);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    /**
     * Obtiene el siguiente número de secuencia para una conversación de un usuario con un agente.
     *
     * @param int $user_id ID del usuario.
     * @param string $agent_name Nombre del agente.
     * @return int Número de secuencia siguiente, incrementado en 1 si existe, o 1 si es el primero.
     */
    private function get_next_sequence($user_id, $agent_name) {
        $conn = connect_to_db();
        $sql = "SELECT MAX(sequence) AS max_sequence FROM conversations WHERE user_id = ? AND agent = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("is", $user_id, $agent_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        
        return ($row['max_sequence'] !== null) ? $row['max_sequence'] + 1 : 1;
    }
}

class ToolsDefinition {
    private $tools;

    /**
     * Constructor de la clase ToolsDefinition.
     *
     * @param array $tools Arreglo de herramientas disponibles, donde cada herramienta contiene
     *                     información como el callback, la descripción y los parámetros.
     */
    public function __construct($tools) {
        $this->tools = $tools;
    }

    /**
     * Ejecuta una herramienta específica con los argumentos dados.
     *
     * @param string $tool_name Nombre de la herramienta a ejecutar.
     * @param mixed $tool_arguments Argumentos que se pasarán a la herramienta.
     * @return mixed El resultado de la ejecución de la herramienta.
     * @throws Exception Si la herramienta no está definida.
     */
    public function execute_tool($tool_name, $tool_arguments) {
        if (isset($this->tools[$tool_name])) {
            $tool_executable = $this->tools[$tool_name]['callback'];
            return call_user_func($tool_executable, $tool_arguments);
        } else {
            throw new Exception("La herramienta $tool_name no ha sido definida.");
        }
    }

    /**
     * Obtiene todas las herramientas definidas en el objeto.
     *
     * @return array Arreglo de herramientas disponibles.
     */
    public function get_tools() {
        return $this->tools;
    }

    /**
     * Obtiene la descripción de una herramienta específica.
     *
     * @param string $tool_name Nombre de la herramienta.
     * @return string Descripción de la herramienta, o 'No desc' si no tiene una descripción.
     */
    public function get_tool_description($tool_name) {
        return $this->tools[$tool_name]['description'] ?? 'No desc';
    }

    /**
     * Obtiene los parámetros de una herramienta específica.
     *
     * @param string $tool_name Nombre de la herramienta.
     * @return array Arreglo de parámetros de la herramienta o un arreglo vacío si no tiene parámetros.
     */
    public function get_tool_parameters($tool_name) {
        return $this->tools[$tool_name]['parameters'] ?? [];
    }

    /**
     * Genera una descripción en formato JSON de los parámetros de una herramienta específica,
     * incluyendo el nombre, descripción, obligatoriedad y tipo de cada parámetro.
     *
     * @param string $tool_name Nombre de la herramienta.
     * @return string Descripción en formato JSON de los parámetros de la herramienta.
     */
    public function get_parameters_description($tool_name) {
        $parameters = $this->get_tool_parameters($tool_name);
        $parameters_description = '[';
        foreach($parameters as $param_name => $param_details) {
            $param_description = $param_details['description'];
            $param_required = $param_details['required'] ? 'true' : 'false';
            $param_type = $param_details['type'];
            $parameters_description .= <<<EOD
            {"name" : "$param_name", "description" : "$param_description", "required" : $param_required, "type" : "$param_type"},
            EOD;
        }
        $parameters_description = rtrim($parameters_description, ',') . ']';
        return $parameters_description;
    }

    /**
     * Genera un prompt en formato JSON que incluye todas las herramientas, con su nombre,
     * descripción y parámetros, listo para ser utilizado en un contexto de generación de texto.
     *
     * @return string Prompt en formato JSON de todas las herramientas.
     */
    public function get_tools_prompt() {
        $tools_prompt = "";

        foreach($this->tools as $tool_name => $tool_details) {
            $tool_description = $this->get_tool_description($tool_name);
            $parameters_description = $this->get_parameters_description($tool_name);
            $tools_prompt .= <<<EOD
            {
                "name" : "$tool_name",
                "description" : "$tool_description",
                "parameters" : $parameters_description
            },
            EOD;
        }
        return rtrim($tools_prompt, ',');
    }
}

class Orchestrator {
    protected $orchestrator_template;
    protected LLMClient $llm_client;
    protected ToolsDefinition $tools;

    /**
     * Constructor de la clase Orchestrator.
     *
     * @param string $orchestrator_template Plantilla para el prompt del orquestador.
     * @param LLMClient $llm_client Cliente de LLM que maneja las solicitudes de generación.
     * @param ToolsDefinition $tools Definición de herramientas disponibles para el orquestador.
     */
    public function __construct($orchestrator_template, $llm_client, $tools) {
        $this->orchestrator_template = $orchestrator_template;
        $this->llm_client = $llm_client;
        $this->tools = $tools;
    }

    /**
     * Ejecuta el flujo de conversación, procesando el mensaje del usuario y el historial.
     *
     * @param string $message Mensaje enviado por el usuario.
     * @param array $conversation_history Historial de conversación anterior.
     * @return array Respuesta del agente y resultados de las herramientas usadas.
     */
    public function run($message, $conversation_history=[]) {
        $prepared_partial_prompt = $this->prepare_orchestrator_prompt();
        $messages = array_merge($conversation_history, [
            ['system', $prepared_partial_prompt],
            ['user', $message]
        ]);
        
        $response = $this->llm_client->create_completion($messages);
        $tool_calls = $this->parse_tool_calls($response);
        $messages = array_merge($messages, [
            ['assistant', $response]
        ]);

        if ($tool_calls) {
            $tool_outputs = [];
            foreach ($tool_calls as $tool_call) {
                $tool_name = $tool_call['tool_name'];
                $tool_arguments = $tool_call['tool_arguments'];
                $tool_output = trim($this->tools->execute_tool($tool_name, $tool_arguments));
                array_push($tool_outputs, $tool_output);
                $messages = array_merge($messages, [
                    ['system', $tool_output]
                ]);
            }
            $response = $this->llm_client->create_completion($messages);
            $final_answer = $this->parse_final_answer($response);
            return ['content' => $final_answer, 'tool_outputs' => $tool_outputs];
        }
        $final_answer = $this->parse_final_answer($response);
        if (!$final_answer) {
            $final_answer = $response;
        }
        return ['content' => $final_answer, 'tool_outputs' => null];
    }

    /**
     * Prepara el prompt del orquestador, incluyendo las herramientas disponibles.
     *
     * @return string Prompt preparado para el orquestador.
     */
    private function prepare_orchestrator_prompt() {
        $tools_prompt = $this->tools->get_tools_prompt();
        $prepared_prompt = str_replace('{tools}', $tools_prompt, $this->orchestrator_template);
        return $prepared_prompt;
    }

    /**
     * Analiza las llamadas a herramientas en la respuesta del LLM.
     *
     * @param string $response Respuesta generada por el LLM.
     * @return array Arreglo con las llamadas a herramientas encontradas en el texto.
     */
    private function parse_tool_calls($response) {
        $pattern = '/<tool_calls>(.*?)<\/tool_calls>/s';
        preg_match($pattern, $response, $matches);
        return json_decode($matches[1] ?? '', true);
    }

    /**
     * Extrae la respuesta final del asistente desde el texto de respuesta.
     *
     * @param string $response Respuesta generada por el LLM.
     * @return string Respuesta final del asistente.
     */
    private function parse_final_answer($response) {
        $pattern = '/<final_answer>(.*?)<\/final_answer>/s';
        preg_match($pattern, $response, $matches);
        return $matches[1] ?? '';
    }
}

class Agent {
    private Orchestrator $orchestrator;
    private Memory $memory;
    private $agent_name;
    private $agent_prompt;

    /**
     * Constructor de la clase Agent.
     *
     * @param Orchestrator $orchestrator Objeto Orchestrator para gestionar el flujo de conversación.
     * @param Memory $memory Objeto Memory para manejar el almacenamiento del historial de conversación.
     * @param string $agent_name Nombre del agente.
     * @param string $agent_prompt Prompt inicial del agente.
     */
    public function __construct($orchestrator, $memory, $agent_name, $agent_prompt) {
        $this->orchestrator = $orchestrator;
        $this->memory = $memory;
        $this->agent_name = $agent_name;
        $this->agent_prompt = $agent_prompt;
    }

    /**
     * Ejecuta la conversación del agente, gestionando el mensaje del usuario.
     *
     * @param string $message Mensaje del usuario.
     * @return array Respuesta del agente, incluyendo la salida de las herramientas usadas.
     */
    public function run($message) {

        $user_id = $_SESSION['user']['id'];
        $max_conversation_history = $this->memory->get_max_conversation_history($user_id, $this->agent_name);

        $prompt = array_merge([
            ['system', 'Currently speaking to: ' . json_encode($this->get_user_info())],
            ['system', 'Past conversations: ']
        ], $max_conversation_history);

        $prompt = array_merge($prompt, [['system', $this->agent_prompt]]);

        $response = $this->orchestrator->run($message, $prompt);
        $this->memory->add_conversation_thread($user_id, $this->agent_name, 'user', $message);
        $this->memory->add_conversation_thread($user_id, $this->agent_name, 'assistant', $response['content']);
        return $response;
    }

    /**
     * Muestra el historial de conversación en el formato HTML para el chat.
     */
    public function display_chat_history() {
        $user_id = $_SESSION['user']['id'];
        $full_name = $_SESSION['user']['full_name'];

        $conversation_history = $this->memory->get_conversation_history($user_id, $this->agent_name);
        $conversation_history = array_reverse($conversation_history);
        foreach ($conversation_history as $message) {
            if ($message['role'] == 'user') {
                $icon = '<span class="is-size-4">👤</span>';
                $display_name = ucfirst($full_name);
                $container_class = 'notification-container';
                $class = 'is-success';
            } elseif ($message['role'] == 'assistant') {
                $icon = '<span class="is-size-4">🤖</span>';
                $display_name = ucfirst($this->agent_name);
                $container_class = 'notification-container assistant';
                $class = '';
            } else {
                $display_name = ucfirst($this->agent_name);
                $container_class = 'notification-container';
                $class = 'is-info';
            }
            
            $message_content = $message['content'];

            echo <<<EOD
                <div class="$container_class">
                    <div class="notification $class" style="width: 90%;">
                        $icon <strong>$display_name:</strong> $message_content
                    </div>
                </div>
            EOD;
        }
    }

    /**
     * Obtiene la información del usuario desde la sesión.
     *
     * @return array Información relevante del usuario.
     */
    private function get_user_info() {
        $user_info = [
            'user_id' => $_SESSION['user']['id'],
            'username' => $_SESSION['user']['username'],
            'full_name' => $_SESSION['user']['full_name'],
            'role' => $_SESSION['user']['role'],
            'department' => $_SESSION['user']['department']
        ];
        return $user_info;
    }

    /**
     * Elimina el historial de conversación del usuario con el agente.
     */
    public function delete_history() {
        $user_id = $_SESSION['user']['id'];
        $this->memory->delete_conversation_history($user_id, $this->agent_name);
    }
}

?>