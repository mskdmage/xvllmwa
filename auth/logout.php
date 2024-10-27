<?php

/**
 * Este script cierra la sesión de un usuario.
 * 1. Inicia la sesión (si no está ya iniciada) para manipularla.
 * 2. Destruye todas las variables de la sesión y la termina.
 * 3. Redirige al usuario a la página de instrucciones después de cerrar la sesión.
 */
session_start();
require('../config/config.php');
session_unset();
session_destroy();
header("Location: $WEBROOT/instructions"); 
exit();