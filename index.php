<?php

require_once "sistema/configuracao.php";
include_once "Helpers.php";



// if(validarEmail('teste@gmailcom')){
//     echo "Endereço de e-mail Valido";
// }else{
//     echo "E-mail invalido";
// }


if(validarUrl('TESTE')){
    echo "Endereço valido";
}else{
    echo "Endereço invalido";
}