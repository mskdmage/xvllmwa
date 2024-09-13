<?php
$WEBROOT = "/xvllmwa";
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . $WEBROOT;

/* DATABASE */
$DB_SERVERNAME = "localhost";
$DB_USERNAME = "root";
$DB_PASSWORD = "";

function connectDB($select_db = false) {
    global $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD;
    $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);
    if ($conn->connect_error) {
        die("Error en la conexion: " . $conn->connect_error);
    }

    if ($select_db) {
        $conn->select_db('xvllmwa');
    }

    return $conn;
}
?>