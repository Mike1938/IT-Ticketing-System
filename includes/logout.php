<?php
// * This is to destroy the session from employees or clients and send them back to the index page
session_start();
$_SESSION = array();
setcookie(session_name(), '', time() - 2592000, '/');
session_destroy();
header("location: http://localhost/finalProyect/index.php")
?>