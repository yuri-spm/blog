<?php

namespace system\Core;
use system\Core\Session;
use Exception;

class Helpers
{
        
    /**
     * flash
     *
     * @return string
     */
    public static function flash(): ?string
    {
        $session = new Session();
        if($flash = $session->flash()){
            echo $flash;
        }
        return null;
    }

    /**
     * Redireciona para a url informada
     * @param string $url
     * @return void
     */
    public static function redirect(string $url = null): void
    {
        header('HTTP/1.1 302 Found');

        $local = ($url ? self::url($url) : self::url());

        header("Location: {$local} ");
        exit();
    }

    /**
     * Válida um número de CPF
     * @param string $cpf
     * @return bool
     */
    public static function validateCpf(string $cpf): bool
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
     * Limpa todos os caracteres não numéricos
     * @param string $number
     * @return string
     */
    public static function clearNumber(string $number): string
    {
        return preg_replace('/[^0-9]/', '', $number);
    }

    /**
     * Gera url amigável
     * @param string $string
     * @return string slug
     */
    public static function slug(string $string): string
    {
        $mapa['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?¨|;:.,\\\'<>°ºª  ';

        $mapa['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
        $slug = strtr(utf8_decode($string), utf8_decode($mapa['a']), $mapa['b']);
        $slug = strip_tags(trim($slug));
        $slug = str_replace(' ', '-', $slug);
        $slug = str_replace(['-----', '----', '---', '--', '-'], '-', $slug);

        return strtolower(utf8_decode($slug));
    }

    /**
     * Data atual formatada 
     * @return string
     */
    public static function dataAtual(): string
    {
        $dayMonth = date('d');
        $dayWeekend = date('w');
        $month = date('n') - 1;
        $ano = date('Y');

        $namesDiasDaSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sabádo'];

        $namesDosMeses = [
            'janeiro',
            'fevereiro',
            'março',
            'abril',
            'maio',
            'junho',
            'julho',
            'agosto',
            'setembro',
            'outubro',
            'novembro',
            'dezembro'
        ];

        $dateFormat = $namesDiasDaSemana[$dayWeekend] . ', ' . $dayMonth . ' de ' . $namesDosMeses[$month] . ' de ' . $ano;

        return $dateFormat;
    }

    /**
     * Monta url de acordo com o environment 
     * @param string $url parte da url ex. admin
     * @return string url completa
     */
    public static function url(string $url = null): string
    {
        $server = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $environment  = ($server == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);

        if (str_starts_with($url, '/')) {
            return $environment  . $url;
        }
        return $environment  . '/' . $url;
    }

    /**
     * Checa se o server é localhost
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
     * Valida uma url
     * @param string $url
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

    public static function validateUrlFilter(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Valida um endereço de e-mail
     * @param string $email
     * @return bool
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Conta o tempo decorrido de uma data
     * @param string $data
     * @return string
     */
    public static function countTime(string $data): string
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $time = strtotime($data);
        $difference = $now - $time;

        $second  = $difference;
        $minutes  = round($difference / 60);
        $horas = round($difference / 3600);
        $day = round($difference / 86400);
        $weeks = round($difference / 604800);
        $months  = round($difference / 2419200);
        $years  = round($difference / 29030400);

        if ($second  <= 60) {
            return 'agora';
        } elseif ($minutes  <= 60) {
            return $minutes  == 1 ? 'há 1 minuto' : 'há ' . $minutes  . ' minutos';
        } elseif ($horas <= 24) {
            return $horas == 1 ? 'há 1 hora' : 'há ' . $horas . ' horas';
        } elseif ($day <= 7) {
            return $day == 1 ? 'ontem' : 'há ' . $day . ' dias';
        } elseif ($weeks <= 4) {
            return $weeks == 1 ? 'há 1 semana' : 'há ' . $weeks . ' semanas';
        } elseif ($months  <= 12) {
            return $months  == 1 ? 'há 1 mês' : 'há ' . $months  . ' meses';
        } else {
            return $years  == 1 ? 'há 1 ano' : 'há ' . $years  . ' anos';
        }
    }

    /**
     * Formata um value com ponto e virgula
     * @param float $value
     * @return string
     */
    public static function formaterValue(float $value = null): string
    {
        return number_format(($value ? $value : 0), 2, ',', '.');
    }

    /**
     * Formata um número com pontos
     * @param int $number
     * @return string
     */
    public static function formaterNumber(int $number = null): string
    {
        return number_format($number ?: 0, 0, '.', '.');
    }

    /**
     * Saudação de acordo com o horário
     * @return string saudação
     */
    public static function greetings(): string
    {
        $hora = date('H');

        $saudacao = match (true) {
            $hora >= 0 and $hora <= 5 => 'boa madrugada',
            $hora >= 6 and $hora <= 12 => 'bom dia',
            $hora >= 13 and $hora <= 18 => 'boa tarde',
            default => 'boa noite'
        };

        return $saudacao;
    }

    /**
     * Resume um texto
     * 
     * @param string $texto texto para resumir
     * @param int $limit quantidade de caracteres
     * @param string $continue opcional - o que deve ser exibido ao final do resumo
     * @return string texto resumido
     */
    public static function summarizeText(string $texto, int $limit, string $continue = '...'): string
    {
        $cleanText  = trim(strip_tags($texto));
        if (mb_strlen($cleanText ) <= $limit) {
            return $cleanText ;
        }

        $summarizeText = mb_substr($cleanText , 0, mb_strrpos(mb_substr($cleanText , 0, $limit), ''));

        return $summarizeText . $continue;
    }

}
