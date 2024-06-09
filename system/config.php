<?php

//Arquivo de configuração do sistema
//define o fuso horario
date_default_timezone_set('America/Sao_Paulo');

//data de acesso ao banco de data
define('DB_HOST', 'localhost');
define('DB_PORTA', '3306');
define('DB_NOME', 'blog');
define('DB_USUARIO', 'root');
define('DB_SENHA', '');

//informações do sistema
define('SITE_NOME', 'Helpx');
define('SITE_DESCRICAO', 'Helpx - Tecnologia em Sistemas');

//urls do sistema
define('URL_PRODUCAO', 'https://helpx.com.br');
define('URL_DESENVOLVIMENTO', 'http://localhost/blog');

define('URL_SITE', 'blog/');
define('URL_ADMIN', 'blog/admin/');

