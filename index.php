<?php

//Arquivo index responsável pela inicialização do sistema

use system\Core\Connect;
use system\Core\Session;
use system\Model\PostModel;

require 'vendor/autoload.php';

require 'routers.php';

// $session = new Session();

// $session->create('user', ['id' => 10, 'nome' => 'Yuri do Monte']);

// var_dump($session->load());

// var_dump($session->check('user'));

// // var_dump($session->clean('user'));

// // var_dump($session->check('user'));

// var_dump($session->deleted());

// var_dump($session->check('nome'));