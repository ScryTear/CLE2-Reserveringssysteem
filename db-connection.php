<?php
// Gegevens voor de connectie
$host       = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'planning_system';

$db = mysqli_connect($host, $username, $password, $database)
    or die('Error: '.mysqli_connect_error());
?>
