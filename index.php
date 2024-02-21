<?php

//Arquivo index responsável pela inicialização do sistema

use system\Core\Connect;
use system\Model\PostModel;

require 'vendor/autoload.php';

// require 'routers.php';

$posts = (new PostModel())->find(2);

foreach($posts as $post){
    var_dump($post);
}

