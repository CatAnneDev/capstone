<?php
session_start();
include "functions.php";
// connect to database using PDO (PHP Data Objects) MySQL
$conn = pdo_connect_mysqli();
// default page is login.php when a new visitor arrives
$page = isset($_GET["page"]) && file_exists($_GET["page"] . ".php") ? $_GET["page"] : "login";
include $page . ".php";
?>