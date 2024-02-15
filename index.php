<?php

require_once "sistema/configuracao.php";
include_once "Helpers.php";



$numero = 5;

while($numero < 10){
    echo $numero++;
}

echo'<hr>';

for($i=1; $i<=$numero; $i++){
    echo $i;
}

echo'<hr>';

var_dump(validaCPF(15994140719));
