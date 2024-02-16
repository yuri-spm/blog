<?php

use system\Core\Helpers;
use system\Core\Message;

require_once "system/config.php";
include_once "./system/Core/Helpers.php";
include './system/Core/Message.php';

echo'<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">';

echo (new Message)->success('Aula 43');

echo '<hr>';


var_dump(Helpers::validateCPF('14594140718'));



