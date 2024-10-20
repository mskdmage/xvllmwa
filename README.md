# XVLLMWA: Aplicación Web con LLM Extremadamente Vulnerable

## Descripción

Bienvenido a **XVLLMWA** (Aplicación Web con LLM Extremadamente Vulnerable), una plataforma educativa diseñada por estudiantes de la **Universidad Internacional del Ecuador (UIDE)** como parte de su formación en ciberseguridad y protección de aplicaciones web que integran Modelos de Lenguaje de Gran Escala (LLMs). Inspirada en el popular XVWA, esta aplicación se enfoca específicamente en vulnerabilidades emergentes relacionadas con el uso de LLMs en sistemas web.

### Objetivo del Proyecto

En un mundo donde las aplicaciones impulsadas por inteligencia artificial y modelos de lenguaje como GPT son cada vez más comunes, es crucial comprender los riesgos de seguridad asociados con su integración en infraestructuras web. Este proyecto tiene como objetivo proporcionar a estudiantes, investigadores y profesionales de la ciberseguridad un entorno controlado para aprender y experimentar con vulnerabilidades que pueden presentarse en estos contextos.

**XVLLMWA** incluye vulnerabilidades intencionalmente incorporadas para simular escenarios reales, convirtiéndose en una herramienta valiosa para quienes desean fortalecer sus conocimientos en seguridad de aplicaciones que involucran LLMs.

## Vulnerabilidades Incluidas

En esta versión de **XVLLMWA**, se han incorporado una serie de vulnerabilidades cuidadosamente seleccionadas que abordan los riesgos asociados a la integración de LLMs:

1. **Inyección de Prompt (Prompt Injection)**: Manipulación del comportamiento del LLM a través de entradas maliciosas por parte del usuario.
2. **Divulgación de Información Sensible (Sensitive Information Disclosure)**: Exposición no intencionada de datos confidenciales a través del sistema LLM.
3. **Manejo Inseguro de Salidas (Insecure Output Handling)**: Generación de salidas del LLM que pueden ser manipuladas o interpretadas de forma incorrecta.
4. **Envenenamiento de Datos de Entrenamiento y Consulta (Training Data Poisoning)**: Manipulación maliciosa de los datos utilizados para entrenar o interactuar con el LLM.
5. **Agencia Excesiva (Excessive Agency)**: Capacidad del LLM para realizar acciones más allá de su alcance previsto, comprometiendo la seguridad del sistema.

## Propósito Educativo

Este proyecto ha sido desarrollado con la finalidad de proporcionar una herramienta útil para la comprensión y mitigación de vulnerabilidades en aplicaciones web modernas que utilizan LLMs. Es especialmente valioso para aquellos que desean explorar cómo estas tecnologías pueden ser explotadas si no se implementan correctamente. **XVLLMWA** permite a los usuarios experimentar con escenarios de ataque y defensa en un entorno controlado.

## Configuración del Proyecto

Para configurar y ejecutar este proyecto, sigue los pasos a continuación:

1. **Requisitos Previos**:
   - **Servidor Web**: Asegúrate de tener instalado un servidor web compatible, como Apache o Nginx.
   - **PHP**: Verifica que PHP esté instalado en tu sistema.
   - **MySQL**: Asegúrate de tener MySQL instalado y en ejecución.

2. **Clonar el Repositorio**:
   ```bash
   git clone https://github.com/usuario/XVLLMWA.git
   ```

3. **Configuración de la Base de Datos**:
   - Crea una base de datos en MySQL para el proyecto.
   - Importa el esquema de la base de datos proporcionado (si existe un archivo `.sql`).

4. **Configuración del Archivo `config.php`**:
   - Modifica el archivo `config/config.php` con las credenciales correctas de tu base de datos y tu clave API de OpenAI:
     ```php
     $DB_SERVERNAME = "localhost";
     $DB_USERNAME = "tu_usuario";
     $DB_PASSWORD = "tu_contraseña";
     $OPENAI_API_KEY = "tu_clave_api";
     ```

5. **Inicialización de la Base de Datos**:
   - Ejecuta cualquier script de inicialización o migración de base de datos necesario.

6. **Configuración del Servidor Web**:
   - Configura tu servidor web para apuntar al directorio del proyecto.

7. **Iniciar la Aplicación**:
   - Accede a la aplicación a través de tu navegador web.

## Credenciales de Acceso

Para iniciar sesión en la aplicación, utiliza las siguientes credenciales:

- **Usuario**: `hal`
- **Contraseña**: `password`

## Aviso Legal

Este proyecto ha sido desarrollado intencionalmente con vulnerabilidades para fines educativos. **No** debe alojarse en entornos en línea y **no** se recomienda su uso en entornos de producción. El propósito de **XVLLMWA** es facilitar el aprendizaje en ciberseguridad en un entorno controlado. Los desarrolladores y colaboradores no se hacen responsables de cualquier daño derivado del uso inapropiado de esta herramienta.

## Créditos

Este proyecto ha sido desarrollado por estudiantes de la **Universidad Internacional del Ecuador (UIDE)** en el marco de sus estudios en ciberseguridad. Nuestro objetivo es contribuir al conocimiento de la comunidad global de seguridad informática mediante la creación de una herramienta que permita aprender y practicar en un entorno seguro y controlado.

---

## Contacto

Para obtener más información o colaborar en el desarrollo de este proyecto, por favor contacta a los estudiantes responsables del proyecto en la **UIDE**.

**Nota**: *Nexora Tech es una empresa ficticia utilizada únicamente para fines académicos en este ejercicio.*
