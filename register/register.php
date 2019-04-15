<?php
require_once( "../php/config.php");
include (ROOT_PATH . "db/init.php");
include (ROOT_PATH . "login/user.php");

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
echo '<response>';
$user = $_GET['user'];

if(user_exists($conn, $user))
    echo $user.' is already being used.';
else if ($user == '')
    echo 'Username must be greater then 4 characters';
else
    echo 'Username is available!';
echo '</response>';
?>