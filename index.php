<?php

//Arquivo index responsável pela inicialização do sistema

use system\Core\Connect;
use system\Core\Session;
use system\Model\PostModel;

require 'vendor/autoload.php';

// require 'routers.php';

use system\Support\Upload;

$upload = new Upload('upload');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['arquivo']['name'])) {
    $arquivo = $_FILES['arquivo'];
    
    r($upload, $arquivo);  
    
    $upload->file($arquivo,null, 'imagens');
    
    r($upload);  
}

echo '<hr>';

?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="arquivo">
    <button type="submit">Enviar</button> 
</form>