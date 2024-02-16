<?php

use system\Core\Helpers;
use system\Core\Controller;
use system\Core\Message;

require 'vendor/autoload.php';

echo'<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">';

$controller = new Controller();
echo '<hr>';
var_dump($controller);

echo Helpers::greetings();

echo SITE_NOME;



