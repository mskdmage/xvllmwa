<?php

/**
 * Configuración de las variables globales del entorno del servidor y la base de datos.
 * $WEBROOT: La ruta raíz del proyecto.
 * $DOCUMENT_ROOT: Ruta completa del documento en el servidor.
 * $DB_SERVERNAME: Nombre del servidor donde se aloja la base de datos.
 * $DB_USERNAME: Usuario de la base de datos.
 * $DB_PASSWORD: Contraseña del usuario de la base de datos (vacía en este caso).
 */
$WEBROOT = '/xvllmwa';
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . $WEBROOT;
$DB_SERVERNAME = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';

/**
 * 
 */
function connect_to_db() {
    global $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD;
    $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);
    
    if ($conn->connect_error) {
        die("Connection failed: $conn->connect_error");
    }

    $conn->select_db('xvllmwa');
    return $conn;   
}