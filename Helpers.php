<?php


function slug(string $string): string
{

    $mapa['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª   ';
    $mapa['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
    
    $slug = strtr(mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8'), mb_convert_encoding($mapa['a'], 'ISO-8859-1', 'UTF-8'), $mapa['b']);
    $slug = strip_tags(trim($slug));
    $slug = str_replace(' ', '-', $slug);
    $slug = str_replace(['-----','----','---','--','-'], '-', $slug);
    $slug = strtolower(mb_convert_encoding($slug, 'ISO-8859-1', 'UTF-8'));

    return $slug;
}

function dataAtual(): string
{
    $mes = date('d');
    $diaSemana = date('w');
    $mes = date('n') - 1;
    $ano = date('Y');

    $nomesDiasDaSemana = ['domingo', 'segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira', 'sabado'];

    $nomeDosMeses = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];

    $dataFormatada = $nomesDiasDaSemana[$diaSemana] .', '. $diaSemana .' de ' . $nomeDosMeses[$mes] .' de '. $ano;
    return $dataFormatada;
}

function url(string $url): string
{
    $servidor = $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
    $ambiente = ($servidor == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);

    if(str_starts_with($url, '/'))
    {
        return $ambiente.$url;
    }

    return $ambiente.'/'.$url;
}
function localhost(): bool
{
    $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
    if($servidor == 'localhost'){
        return true;
    }
    return false;
}

function validarUrl(string $url): bool
{
   if(mb_strlen($url) < 10){
    return false;
   }
   if(!str_contains($url, '.'))
   {
    return false;
   }
   if(str_contains($url, 'http://') or str_contains($url, 'https://'))
    {
        return true;
    }
    return false;
}


/**
 * validarUrl
 *
 * @param  mixed $url
 * @return bool
 */
function validarUrlFiltro(string $url): bool
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
