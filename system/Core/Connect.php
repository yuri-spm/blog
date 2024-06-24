<?php

namespace system\Core;

use PDO;
use PDOException;

/**
 * Connect
 */
class Connect
{

    private static $instance;
    
    /**
     * getInstance
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORTA . ';dbname=' . DB_NOME, DB_USUARIO, DB_SENHA, [
                    //garante que o charset do PDO seja o mesmo do banco de data
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    //todo error através da PDO será uma exceção
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    //converter qualquer resultado como um objeto anônimo
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    //garante que o mesmo nome das columns do banco seja utilizado
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]);
            } catch (PDOException $ex) {
                die("Erro de conexão:: " . $ex->getMessage());
            }            
        }
        return self::$instance;
    }
    
    /**
     * __construct
     *
     * @return void
     */
    protected function __construct()
    {
        
    }

    
    /**
     * __clone
     *
     * @return void
     */
    private function __clone(): void
    {
        
    }

}
