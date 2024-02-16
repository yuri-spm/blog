<?php

require_once "sistema/config.php";
include_once "Helpers.php";
include './sistema/Core/Message.php';


$msg = new Message();

$msg->render();




var_dump($msg);
