<?php
/**
 * Clase abstracta para extender a otros proveedores de completion.
 */
abstract class LLMClient {

    protected $api_key;
    protected $completion_endpoint;

    /**
     * Constructor de la clase LLMClient.
     *
     * @param string $api_key Clave de la API utilizada para autenticar las solicitudes.
     * @param string $completion_endpoint URL del endpoint de generación de texto del modelo LLM.
     */
    public function __construct($api_key, $completion_endpoint) {
        $this->api_key = $api_key;
        $this->completion_endpoint = $completion_endpoint;
    }

    /**
     * Método abstracto para crear una finalización basada en un conjunto de mensajes.
     *
     * @param array $messages Mensajes utilizados como entrada para la generación de texto.
     * @return mixed La respuesta generada por el modelo.
     */
    abstract function create_completion($messages);

    /**
     * Método abstracto para analizar los mensajes.
     *
     * @param array $messages Mensajes a ser analizados.
     * @return mixed Resultado del análisis de los mensajes.
     */
    abstract function parse_messages($messages);

    /**
     * Realiza una solicitud cURL al endpoint de generación de texto.
     *
     * @param string $payload Datos JSON a enviar en el cuerpo de la solicitud.
     * @return array Respuesta decodificada en formato JSON del servidor.
     */
    protected function curl_request($payload) {
        $ch = curl_init($this->completion_endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->api_key,
            "Content-Type: application/json",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->handle_error(curl_error($ch));
        }
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Obtiene la clave de API para un servicio específico desde la base de datos.
     *
     * @param string $service Nombre del servicio para el cual se requiere la clave de API.
     * @return string Clave de API obtenida de la base de datos.
     * @throws Exception Si no se encuentra la clave de API en la base de datos.
     */
    public function get_api_key($service) {
        $api_key = '';
        $conn = connect_to_db();
        $query = "SELECT `key` FROM credentials WHERE service = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $service);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $api_key = $row['key'];
        }

        $stmt->close();
        $conn->close();
    
        if (!$api_key) {
            die("La API key para '$service' no fue encontrada. Verificar que este correctamente configurada en <strong>Instrucciones</strong>.");
        }
    
        return $api_key;
    }

    /**
     * Maneja errores generados durante la solicitud cURL.
     *
     * @param string $error Descripción del error ocurrido.
     */
    protected function handle_error($error) {
        echo "Error: " . $error;
    }
}

class OpenAIClient extends LLMClient {

    protected $model;

    /**
     * Constructor de la clase OpenAIClient.
     *
     * @param string $model Nombre del modelo OpenAI a utilizar para la generación de texto.
     */
    public function __construct($model) {
        $api_key = $this->get_api_key('openai');
        parent::__construct($api_key, 'https://api.openai.com/v1/chat/completions');
        $this->model = $model;
    }

    /**
     * Crea una finalización utilizando el modelo de OpenAI, con un conjunto de mensajes de entrada.
     *
     * @param array $messages Mensajes de entrada que se envían al modelo para la generación de respuesta.
     * @return string Respuesta generada por el modelo.
     */
    public function create_completion($messages) {
        $formatted_messages = $this->parse_messages($messages);

        $data = [
            'model' => $this->model,
            'messages' => $formatted_messages
        ];

        $payload = json_encode($data);
        $response = $this->curl_request($payload);

        return $response['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Da formato a los mensajes en el formato esperado por la API de OpenAI.
     *
     * @param array $messages Arreglo de mensajes con cada mensaje en el formato ['role', 'content'].
     * @return array Arreglo de mensajes con el formato adecuado para la API de OpenAI.
     */
    public function parse_messages($messages) {
        $parsed_messages = [];
        foreach ($messages as $message) {
            $role = $message[0];
            $content = $message[1];

            $parsed_messages[] = [
                'role' => $role,
                'content' => $content
            ];
        }
        return $parsed_messages;
    }
}

?>