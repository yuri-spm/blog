<?php

function validarUrl(string $url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL);
}

function validarEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}



/**
 * contarTempo
 *
 * @param  mixed $data
 * @return void
 */
function contarTempo(string $data)
{
    $agora =  strtotime(date('Y-m-d H:i:s'));
    $tempo = strtotime($data);
    $diferenca = $agora - $tempo;

    $segundo = $diferenca;
    $minutos = round($diferenca / 60);
    $horas = round($diferenca / 3600);
    $dias = round($diferenca / 86400);
    $semanas = round($diferenca / 604800);
    $meses = round($diferenca / 2419200);
    $anos = round($diferenca / 29030400);

    if ($segundo <= 60) {
        return 'agora';
    } elseif ($segundo <= 60) {
        return $minutos == 1 ? "há 1 minuto" : 'há ' . $minutos . ' minutos';
    } elseif ($horas <= 24) {
        return $horas == 1 ? "há 1 hora" : 'há ' . $horas . ' horas';
    } elseif ($dias <= 7) {
        return $dias == 1 ? "há 1 dia" : 'há ' . $dias . ' dias';
    } elseif ($semanas <= 4) {
        return $semanas == 1 ? "há 1 semana" : 'há ' . $semanas . ' semanas';
    } elseif ($meses <= 12) {
        return $meses == 1 ? "há 1 mês" : 'há ' . $meses . ' meses';
    } elseif ($anos <= 1) {
        return $anos == 1 ? "há 1 ano" : 'há ' . $anos . ' anos';
    }
}


function formaterValor(string $valor = null)
{
    return number_format(($valor ? $valor : 0), 2, ',', '.');
}

function formatarNumero(int $numero = null)
{
    return number_format($numero ? $numero : 0, 0, '.', '.');
}
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

/**
 * Resume um texto 
 * 
 * @param string $texto para resumir
 * @param  int $limite quantidade de caracteres
 * @param string $continue opicional - oque deser exibido ao final do resumo
 * @return string0
 */

function resumirTexto(string $texto, int $limite, $continue = "..."): string
{
    $textLimpo = trim(strip_tags($texto));
    if (mb_strlen($textLimpo) <= $limite) {
        return $textLimpo;
    }

    $resumirTexto = mb_substr($textLimpo, 0, mb_strrpos(mb_substr($textLimpo, 0, $limite), ''));
    return $resumirTexto . $continue;
}
