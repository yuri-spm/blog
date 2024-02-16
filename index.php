<?php

require_once "sistema/config.php";
include_once "Helpers.php";
include './sistema/Core/Message.php';

echo'<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">';

$msg = new Message();

echo $msg->success('Mensagem de Sucesso')->render();
echo $msg->error('Mensagem de Sucesso')->render();
echo $msg->alert('Mensagem de Sucesso')->render();
echo $msg->info('Mensagem de Sucesso')->render();

$msg->render();


var_dump($msg);
