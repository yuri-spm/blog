<?php

require_once "sistema/configuracao.php";
include_once "Helpers.php";


$text = 'texto para resumir';

// echo $total = mb_strlen(trim($text));

// echo '<hr>';

// echo $resumo = mb_substr($text, 2, 4);

// echo '<hr>';
// echo $ocorrer = mb_strrpos($text, 'x');

echo resumirTexto($text, 9);