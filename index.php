<?php

use system\Core\Helpers;
use system\Core\Message;
use system\Core\Controller;


require_once "system/config.php";
include_once "./system/Core/Helpers.php";
include './system/Core/Message.php';
include './system/Core/Controller.php';

echo'<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">';

$controller = new Controller();
echo '<hr>';
var_dump($controller);



