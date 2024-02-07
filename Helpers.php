<?php

function saudacao(): string
{
    $hora = date('H');

    if ($hora >= 0 && $hora <= 5) {
        $saudacao = 'Boa madrugada';
    } elseif ($hora >= 6 && $hora <= 12) {
        $saudacao = 'Bom dia';
    } elseif ($hora >= 13 && $hora <= 18) {
        $saudacao = 'Boa tarde';
    } else {
        $saudacao = 'Boa noite ';
    }
    return $saudacao;
}

function resumirTexto(string $texto, int $limite, $continue = "..."): string
{
    $textLimpo = trim($texto);
    if (mb_strlen($textLimpo) <= $limite) {
        return $textLimpo;
    }

    $resumirTexto = mb_substr($textLimpo, 0, mb_strrpos(mb_substr($textLimpo, 0, $limite), ''));
    return $resumirTexto.$continue;
}
