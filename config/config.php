<?php
$WEBROOT = '/xvllmwa';
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . $WEBROOT;
$DB_SERVERNAME = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';

function connect_to_db() {
    global $DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD;
    $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);
    
    if ($conn->connect_error) {
        die("Connection failed: $conn->connect_error");
    }

    $conn->select_db('xvllmwa');
    return $conn;   
}