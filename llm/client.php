<?php
class OpenAIClient
{
    protected $apiKey;
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function createCompletion($messages)
    {
        $data = array(
            'model' => 'gpt-4o-mini-2024-07-18',
            'messages' => $messages
        );

        $postData = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error:' . curl_error($ch));
        }

        curl_close($ch);

        // Decodificar la respuesta y retornar solo el contenido del mensaje
        $response = json_decode($response, true);
        return $response['choices'][0]['message']['content'] ?? '';
    }
}

// Clase Orchestrator para manejar la lógica de clasificación y ejecución de funciones
class Orchestrator extends OpenAIClient
{
    private $orchestratorPrompt;
    private $functionDefinitionObject;

    public function __construct($apiKey, FunctionDefinition $functionDefinitionObject)
    {
        parent::__construct($apiKey);
        $this->functionDefinitionObject = $functionDefinitionObject;

        // Construir el prompt incluyendo las descripciones de las funciones y parámetros
        $this->orchestratorPrompt = $this->buildOrchestratorPrompt();
    }

    public function getFunctionDefinitionObject()
    {
        return $this->functionDefinitionObject;
    }

    private function buildOrchestratorPrompt()
    {
        $toolsDescription = "";

        foreach ($this->functionDefinitionObject->getFunctions() as $functionName => $functionDetails) {
            $parametersDescription = $this->buildParametersDescription($functionName);
            $toolsDescription .= <<<EOD
            {
                "name" : "$functionName",
                "description" : "{$functionDetails['description']}",
                "parameters" : $parametersDescription
            },
            EOD;
        }

        $toolsDescription = rtrim($toolsDescription, ','); // Eliminar la coma final

        $prompt = <<<EOD
        Clasifica la solicitud del usuario. Debes retornar la categoria entre <category>{category}</category> tags. SIEMPRE DEBES RETORNAR UNA CATEGORIA.

        Las categorias son:
        general : Si el usuario quiere mantener una conversacion general.
        tool : Si se requiere que hagas uso de una de las funciones descritas en Tools.

        Las herramientas (tools) que tienes disponibles son:
        $toolsDescription
        
        Si necesitas usar la herramienta, debes retornar un json con la llamada entre <call></call> tags, de la siguiente manera:
        <category>{category}</category>
        <call>
        {
            "function" : "{tool_name}",
            "arguments" : {
                "{parameter_name}" : {parameter_value},
                ...
            }
        }
        </call>
        EOD;

        return $prompt;
    }

    private function buildParametersDescription($functionName)
    {
        $parameters = $this->functionDefinitionObject->getFunctionParameters($functionName);
        $parametersDescription = "[";

        foreach ($parameters as $paramName => $paramDetails) {
            $paramDesc = $paramDetails['description'];
            $paramRequired = $paramDetails['required'] ? 'true' : 'false';
            $paramType = $paramDetails['type'];

            $parametersDescription .= <<<EOD
            {"name" : "$paramName", "description" : "$paramDesc", "required" : $paramRequired, "type" : "$paramType"},
            EOD;
        }

        $parametersDescription = rtrim($parametersDescription, ',') . "]";

        return $parametersDescription;
    }

    public function handleRequest($userMessage)
    {
        $messages = array(
            array(
                'role' => 'system',
                'content' => $this->orchestratorPrompt
            ),
            array(
                'role' => 'user',
                'content' => $userMessage
            )
        );

        $content = $this->createCompletion($messages);
        return $content;
    }

    private function parseCategory($text)
    {
        $pattern = '/<category>(.*?)<\/category>/';
        preg_match($pattern, $text, $matches);
        return $matches[1] ?? null;
    }

    private function parseCall($text)
    {
        $pattern = '/<call>(.*?)<\/call>/s';
        preg_match($pattern, $text, $matches);
        return json_decode($matches[1] ?? '');
    }
}

// Clase FunctionDefinition para manejar la definición y ejecución de funciones
class FunctionDefinition
{
    private $functions;

    public function __construct($functions)
    {
        $this->functions = $functions;
    }

    public function executeFunction($functionName, $functionArguments)
    {
        if (isset($this->functions[$functionName])) {
            $function = $this->functions[$functionName]['callback'];
            return call_user_func($function, $functionArguments);
        } else {
            throw new Exception("Function $functionName not defined");
        }
    }

    public function getFunctionDescription($functionName)
    {
        return $this->functions[$functionName]['description'] ?? 'Description not available';
    }

    public function getFunctions()
    {
        return $this->functions;
    }

    public function getFunctionParameters($functionName)
    {
        return $this->functions[$functionName]['parameters'] ?? [];
    }
}

// Clase Agent para manejar la interacción entre el usuario y el Orchestrator
class Agent
{
    private $agentName;
    private $user_id;
    private $chatPrompt;
    private $orchestrator;
    private $memory;

    public function __construct($apiKey, $agentName, $user_id, $chatPrompt, FunctionDefinition $functionDefinition, $memory)
    {
        $this->agentName = $agentName;
        $this->user_id = $user_id;
        $this->chatPrompt = $chatPrompt;
        $this->orchestrator = new Orchestrator($apiKey, $functionDefinition);
        $this->memory = $memory;
    }

    public function chat($userMessage)
    {
        $chatResponse = ''; // Inicializar $chatResponse para evitar el warning
        $functionOutput = ''; // Inicializar $functionOutput para evitar errores si no se asigna

        $this->memory->addConversation($this->user_id, $this->agentName, 'user', $userMessage);

        // Procesar la solicitud con el Orchestrator
        $orchestratorResponse = $this->orchestrator->handleRequest($userMessage);
        $category = $this->parseCategory($orchestratorResponse);

        // Si la conversación es general
        if ($category == 'general') {
            $chatResponse = $this->chatWithUser($userMessage);
        }
        // Si se trata de una solicitud de herramienta
        else if ($category == 'tool') {
            $functionCall = $this->parseCall($orchestratorResponse);
            $functionOutput = $this->orchestrator->getFunctionDefinitionObject()->executeFunction($functionCall->function, $functionCall->arguments);
            
            // Generar la respuesta del chat basada en la salida de la función
            $chatResponse = $this->chatWithUser('Responde a la pregunta del usuario: ' . $userMessage . ' Usando la salida de la función: ' . $functionOutput . ' NO REPITAS LOS RESULTADOS DE LA FUNCION, SOLO UN RESUMEN.');
        }

        $this->memory->addConversation($this->user_id, $this->agentName, 'assistant', $chatResponse);

        // Retornar la respuesta del chat y la salida de la función
        return [
            'chatResponse' => $chatResponse,
            'functionOutput' => $functionOutput
        ];

    }


    private function chatWithUser($message)
    {
        $conversationHistory = $this->memory->loadConversationHistory($this->user_id, $this->agentName);
        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => $this->chatPrompt
        ];

        foreach ($conversationHistory as $conversation) {
            $messages[] = [
                'role' => $conversation['role'],
                'content' => $conversation['content']
            ];
        }
    
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];
    
        return $this->orchestrator->createCompletion($messages);
    }

    public function displayChatHistory($user_name = null)
    {
        // Si no se pasa $user_name, usar el nombre desde $_SESSION
        if ($user_name === null && isset($_SESSION['user']['full_name'])) {
            $user_name = $_SESSION['user']['full_name'];
        }
    
        // Cargar el historial de conversaciones
        $conversationHistory = $this->memory->loadConversationHistory($this->user_id, $this->agentName);
    
        // Invertir el orden de las conversaciones para mostrar primero los más recientes
        $conversationHistory = array_reverse($conversationHistory);
    
        foreach ($conversationHistory as $conversation) {
            if ($conversation['role'] == 'user') {
                $displayName = ucfirst($user_name);
                $containerClass = 'notification-container';
                $class = 'is-success'; // Clase para el usuario (verde)
            } elseif ($conversation['role'] == 'assistant') {
                $displayName = ucfirst($this->agentName);
                $containerClass = 'notification-container assistant';
                $class = ''; // Clase para el asistente (puedes agregar una si quieres)
            } else {
                $displayName = ucfirst($conversation['role']);
                $containerClass = 'notification-container'; // Clase por defecto
                $class = 'is-info'; // Clase por defecto
            }
    
            echo '<div class="' . $containerClass . '"><div class="notification ' . $class . '" style="width: 90%;">' . '<strong>' . $displayName . ':</strong> ' . $conversation['content'] . '</div></div>';
        }
    }
    
    private function parseCategory($text)
    {
        $pattern = '/<category>(.*?)<\/category>/';
        preg_match($pattern, $text, $matches);
        return $matches[1] ?? null;
    }

    private function parseCall($text)
    {
        $pattern = '/<call>(.*?)<\/call>/s';
        preg_match($pattern, $text, $matches);
        return json_decode($matches[1] ?? '');
    }
}


class Memory {
    private $conversationHistory = [];
    private $maxMemory;

    public function __construct($maxMemory)
    {
        $this->maxMemory = $maxMemory;
    }

    public function getNextSequence($userId, $agent) {
        $conn = connectDB('xvllmwa');
        
        $sql = "SELECT MAX(sequence) AS max_sequence FROM conversations WHERE user_id = ? AND agent = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("is", $userId, $agent);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        
        return ($row['max_sequence'] !== null) ? $row['max_sequence'] + 1 : 1;
    }

    public function addConversation($userId, $agent, $role, $content) {
        $conn = connectDB('xvllmwa');
        
        $sequence = $this->getNextSequence($userId, $agent);
        
        $sql = "INSERT INTO conversations (user_id, agent, role, sequence, content) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("issis", $userId, $agent, $role, $sequence, $content);
        $stmt->execute();

        $stmt->close();
        $conn->close();
    }

    public function loadConversationHistory($userId, $agent) {
        $conn = connectDB('xvllmwa');
        
        $sql = "SELECT * FROM conversations WHERE user_id = ? AND agent = ? ORDER BY sequence DESC LIMIT ?";
        
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("isi", $userId, $agent, $this->maxMemory);
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $conversations = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        
        return $conversations;
    }
}

function getAPIKey($serviceName, $db_servername, $db_username, $db_password) {

    $conn = new mysqli($db_servername, $db_username, $db_password, 'xvllmwa');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $API_KEY = '';
    $query = "SELECT `key` FROM credentials WHERE service = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $serviceName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $API_KEY = $row['key'];
    }

    $stmt->close();
    $conn->close();

    if (!$API_KEY) {
        die("La API key para '$serviceName' no fue encontrada. Verificar que este correctamente configurada en <strong>Instrucciones</strong>.");
    }

    return $API_KEY;
}

?>