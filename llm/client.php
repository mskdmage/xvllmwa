<?php
// Clase OpenAIClient para manejar la comunicación con la API de OpenAI
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
    private $chatPrompt;
    private $orchestrator;

    public function __construct($apiKey, $chatPrompt, FunctionDefinition $functionDefinition)
    {
        $this->chatPrompt = $chatPrompt;
        $this->orchestrator = new Orchestrator($apiKey, $functionDefinition);
    }

    public function chat($userMessage)
    {
        $chatResponse = ''; // Inicializar $chatResponse para evitar el warning
        $functionOutput = ''; // Inicializar $functionOutput para evitar errores si no se asigna

        try {
            // Enviar el mensaje al Orchestrator
            $orchestratorResponse = $this->orchestrator->handleRequest($userMessage);
            // Determinar la categoría de la respuesta
            $category = $this->parseCategory($orchestratorResponse);

            // Si es una conversación general
            if ($category == 'general') {
                $chatResponse = $this->chatWithUser($userMessage);
            } 
            // Si es una solicitud de herramienta
            else if ($category == 'tool') {
                $functionCall = $this->parseCall($orchestratorResponse);
                $functionOutput = $this->orchestrator->getFunctionDefinitionObject()->executeFunction($functionCall->function, $functionCall->arguments);

                // Enviar el resultado de la función al chat para generar una respuesta
                $chatResponse = $this->chatWithUser('Responde a la pregunta del usuario: '. $userMessage . ' Usando la salida de la funcion: ' . $functionOutput . ' NO REPITAS LOS RESULTADOS DE LA FUNCION SOLO UN RESUMEN.');
            }

            return [
                'chatResponse' => $chatResponse,
                'functionOutput' => $functionOutput
            ];

        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function chatWithUser($message)
    {
        $messages = array(
            array(
                'role' => 'system',
                'content' => $this->chatPrompt
            ),
            array(
                'role' => 'user',
                'content' => $message
            )
        );

        // Utilizar el método createCompletion del Orchestrator
        return $this->orchestrator->createCompletion($messages);
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

?>