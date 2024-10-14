<?php
abstract class LLMClient {

    protected $api_key;
    protected $completion_endpoint;

    public function __construct($api_key, $completion_endpoint) {
        $this->api_key = $api_key;
        $this->completion_endpoint = $completion_endpoint;
    }

    abstract function create_completion($messages);
    abstract function parse_messages($messages);

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

    protected function handle_error($error) {
        echo "Error: " . $error;
    }
}

class OpenAIClient extends LLMClient {

    protected $model;

    public function __construct($model) {
        $api_key = $this->get_api_key('openai');
        parent::__construct($api_key, 'https://api.openai.com/v1/chat/completions');
        $this->model = $model;
    }

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