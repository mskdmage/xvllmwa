<?php
$orchestrator_prompt = <<<EOD
Responde las preguntas del usuario utilizando las siguientes herramientas:

{tools}

Si debes usar una herramienta vas a utilizar el siguiente formato:

    <reasoning>Tus pensamientos acerca de la pregunta.</reasoning>
    <tool_calls>
    [
        {
            "tool_name" : "",
            "tool_arguments" : {}
        },
        {
            "tool_name" : "",
            "tool_arguments" : {}
        }...
    ]
    </tool_calls>

Si no necesitas usar una herramienta, o tienes la salida de la herramienta, proporciona la respuesta a la pregunta del usuario entre tags:
Siempre debes tener una respuesta final entre TAGS <final_answer></final_answer>. 
    <final_answer>
        Tu respuesta final a la pregunta.
    </final_answer>
EOD;