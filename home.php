<div class="box">
    <figure class="image is-centered mid-size notification has-background-white">
        <img src="<?="$WEBROOT/assets/images/logo.svg"?>" alt="Logo de XVLLMWA">
    </figure>
    <div class="content">

        <h1 class="is-centered">Aplicación Web LLM Extremadamente Vulnerable</h1>
        <p class="has-text-justified">
            Bienvenido a XVLLMWA, una aplicación web diseñada para ofrecer un entorno de aprendizaje avanzado en la protección de aplicaciones que integran Modelos de Lenguaje Extensos (LLMs). Inspirada en el proyecto XVWA (<a href="https://github.com/s4n7h0/xvwa">XVWA en GitHub</a>), esta plataforma introduce vulnerabilidades específicas para aplicaciones Web que integran LLM en su arquitectura. 
            Cabe destacar que esta aplicación ha sido creada intencionalmente vulnerable, por lo que no debe utilizarse como base para proyectos reales ni alojarse en entornos en línea.
        </p>
        <p class="has-text-justified">
            En esta versión, se han incluido una serie de vulnerabilidades cuidadosamente seleccionadas:
            <ul>
                <li>Inyección de Prompt (Prompt Injection)</li>
                <li>Divulgación de Información Sensible (Sensitive Information Disclosure)</li>
                <li>Manejo Inseguro de Salidas (Insecure Output Handling)</li>
                <li>Envenenamiento de Datos de Entrenamiento y Consulta (Training Data Poisoning)</li>
                <li>Excesiva Agencia (Excessive Agency)</li>
            </ul>
        </p>
        <h2>El Escenario</h2>
        <div class="columns">
            <div class="column is-one-half">
                <p class="has-text-justified">
                    <strong>Nexora Tech</strong> es una corporación de tecnología avanzada que ha lanzado un proyecto innovador: una serie de asistentes virtuales impulsados por LLMs de última generación para gestionar vacaciones, politicas, noticas, etc. Estos asistentes están diseñados para mejorar la interacción de los empleados con la información de la empresa y facilitar sus tareas diarias.
                </p>
                <p class="has-text-justified">
                Sin embargo, el proyecto fue implementado con premura, sin la adecuada supervisión de seguridad. El equipo de desarrollo, presionado para cumplir con los plazos, ha descuidado aspectos críticos que podrían comprometer la integridad del sistema.
                </p>
                <p class="has-text-justified">
                    Ahora que trabajas como empleado de Nexora Tech y con tu entusiasmo por la ciberseguridad, tu misión es testear los distintps fallos de seguridad que presenta el sistema.
                </p>
            </div>
            <div class="column is-one-half">
                <div class="card">
                    <div class="card-image">
                        <figure class="image is-4by3">
                            <img src="<?="$WEBROOT/assets/images/nexora_1.png"?>" alt="Escenario Nexora Tech">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="notification is-warning">
    <p>
        <strong>Aviso Legal</strong><br>
        No debes alojar esta aplicación en entornos en línea, especialmente en producción. XVLLMWA es extremadamente vulnerable, y cualquier exposición podría comprometer gravemente su sistema. No nos hacemos responsables de ningún daño. Tu prioridad número uno debe ser siempre la seguridad.
    </p>
</div>