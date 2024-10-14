<?php
session_start();
include('config/config.php');
session_unset();
session_destroy();
header("Location: $WEBROOT/instructions"); 
exit();