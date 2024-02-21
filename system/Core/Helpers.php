<?php

namespace system\Core;

use Exception;

class Helpers
{
    
    /**
     * redirect
     *
     * @param  mixed $url
     * @return void
     */
    public static function redirect(string $url = null)
    {
        header('HTTP/1.1 302 Found');
        $local = ($url ?self::url($url) : self::url() );
        header("Location: {$local}");
        exit();
    }

    /**
     * clearNumber
     *
     * @param  mixed $numero
     * @return string
     */
    public static function clearNumber(string $numero): string
    {
        return preg_replace('/[^0-9]/', '', $numero);
    }

    /**
     * validateCPF
     *
     * @param  mixed $cpf
     * @return bool
     */
    public static function validateCPF(string $cpf): bool
    {
       $cpf = self::clearNumber($cpf);

    if (mb_strlen($cpf) != 11 or preg_match('/(\d)\1{10}/', $cpf)) {
        throw new Exception('O CPF precisa ter 11 digitos');
    }
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            throw new Exception('CPF Inválido');
        }
    }
    return true;
    }


    /**
     * slug
     *
     * @param  mixed $string
     * @return string
     */
    public static function slug(string $string): string
    {

        $map['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª   ';
        $map['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        $slug = strtr(mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8'), mb_convert_encoding($map['a'], 'ISO-8859-1', 'UTF-8'), $map['b']);
        $slug = strip_tags(trim($slug));
        $slug = str_replace(' ', '-', $slug);
        $slug = str_replace(['-----', '----', '---', '--', '-'], '-', $slug);
        $slug = strtolower(mb_convert_encoding($slug, 'ISO-8859-1', 'UTF-8'));

        return $slug;
    }

    /**
     * currentDate
     *
     * @return string
     */
    public static function currentDate(): string
    {
        $day = date('d');
        $dayWee = date('w');
        $month = date('n') - 1;
        $year = date('Y');

        $namesDaysofWeek = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sabado'];

        $nameofMonths = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        $dataFormatada = $namesDaysofWeek[$dayWee] . ', ' . $day . ' de ' . $nameofMonths[$month] . ' de ' . $year;
        return $dataFormatada;
    }

    /**
     * url
     *
     * @param  mixed $url
     * @return string
     */
    public static function url(?string $url = null): string
    {
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $environment = ($server == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);
    
        $url = $url ?? '';
    
        if ($url !== '' && $url[0] === '/') {
            $url = substr($url, 1);
        }
    
        return $environment . '/' . $url;
    }
    
    /**
     * localhost
     *
     * @return bool
     */
    public static function localhost(): bool
    {
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME');
        if ($server == 'localhost') {
            return true;
        }
        return false;
    }

    /**
     * validateUrl
     *
     * @param  mixed $url
     * @return bool
     */
    public static function validateUrl(string $url): bool
    {
        if (mb_strlen($url) < 10) {
            return false;
        }
        if (!str_contains($url, '.')) {
            return false;
        }
        if (str_contains($url, 'http://') or str_contains($url, 'https://')) {
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
    public static function validateUrlFilter(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }



    /**
     * contarTempo
     *
     * @param  mixed $data
     * @return void
     */
    public static function countTime(string $data)
    {
        $now =  strtotime(date('Y-m-d H:i:s'));
        $time = strtotime($data);
        $difference = $now - $time;

        $second = $difference;
        $minutes = round($difference / 60);
        $hours = round($difference / 3600);
        $days = round($difference / 86400);
        $weeks = round($difference / 604800);
        $months = round($difference / 2419200);
        $years = round($difference / 29030400);

        if ($second <= 60) {
            return 'agora';
        } elseif ($second <= 60) {
            return $minutes == 1 ? "há 1 minuto" : 'há ' . $minutes . ' minutos';
        } elseif ($hours <= 24) {
            return $hours == 1 ? "há 1 hora" : 'há ' . $hours . ' horas';
        } elseif ($days <= 7) {
            return $days == 1 ? "há 1 dia" : 'há ' . $days . ' dias';
        } elseif ($weeks <= 4) {
            return $weeks == 1 ? "há 1 semana" : 'há ' . $weeks . ' semanas';
        } elseif ($months <= 12) {
            return $months == 1 ? "há 1 mês" : 'há ' . $months . ' meses';
        } elseif ($years <= 1) {
            return $years == 1 ? "há 1 ano" : 'há ' . $years . ' anos';
        }
    }


    /**
     * formaterValue
     *
     * @param  mixed $valor
     * @return void
     */
    public static function formaterValue(string $valor = null)
    {
        return number_format(($valor ? $valor : 0), 2, ',', '.');
    }

    /**
     * formaterNumber
     *
     * @param  mixed $numero
     * @return void
     */
    public static function formaterNumber(int $numero = null)
    {
        return number_format($numero ? $numero : 0, 0, '.', '.');
    }
    /**
     * greetings
     *
     * @return string
     */
    public static function greetings(): string
    {
        $hours = date('H');
        $greetings = match (true) {
            $hours >= 0 && $hours <= 5 => 'Boa madrugada',
            $hours >= 6 && $hours <= 12 => 'Bom dia',
            $hours >= 13 && $hours <= 18 => 'Boa tarde',
            default => 'Boa noite'
        };
        return $greetings;
    }

    /**
     * Resume um texto 
     * 
     * @param string $texto para resumir
     * @param  int $limite quantidade de caracteres
     * @param string $continue opicional - oque deser exibido ao final do resumo
     * @return string0
     */

    public static function summarizeText(string $texto, int $limit, $continue = "..."): string
    {
        $cleanText = trim(strip_tags($texto));
        if (mb_strlen($cleanText) <= $limit) {
            return $cleanText;
        }

        $summarizeText = mb_substr($cleanText, 0, mb_strrpos(mb_substr($cleanText, 0, $limit), ''));
        return $summarizeText . $continue;
    }
}