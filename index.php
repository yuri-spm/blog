<?php

//Arquivo index responsável pela inicialização do sistema

use system\Core\Connect;
use system\Model\PostModel;

require 'vendor/autoload.php';

// require 'routers.php';

$posts = (new PostModel())->read();

foreach($posts as $post){
    echo $post->title.'<br>';
}
