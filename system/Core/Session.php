<?php

namespace system\Core;

class Session
{

    public function __construct()
    {
        //checa se não existe um ID de sessão
        if (!session_id()) {
            //inicia uma nova sessão ou resume uma sessão existente
            session_start();
        }
    }

    /**
     * Cria uma sessão
     * @param string $key
     * @param mixed $value
     * @return Session
     */
    public function create(string $key, mixed $value): Session
    {
        $_SESSION[$key] = (is_array($value) ? (object) $value : $value);
        return $this;
    }

    /**
     * Carrega uma sessão
     * @return object|null
     */
    public function load(): ?object
    {
        return (object) $_SESSION;
    }

    /**
     * Checa se uma sessão existe
     * @param string $key
     * @return bool
     */
    public function check(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Limpa a sessão especificada
     * @param string $key
     * @return Session
     */
    public function clear(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * Destrói all os data registrados em uma sessão
     * @return Session
     */
    public function delete(): Session
    {
        session_destroy();
        return $this;
    }

    /**
     * __get() é utilizado para ler data de attributes inacessíveis.
     * @param type $attribute
     * @return type
     */
    public function __get($attribute)
    {
        if (!empty($_SESSION[$attribute])) {
            return $_SESSION[$attribute];
        }
    }

    /**
     * Checa ou limpa mensagens flash
     * @return Message|null
     */
    public function flash(): ?Message
    {
        if ($this->check('flash')) {
            $flash = $this->flash;
            $this->clear('flash');
            return $flash;
        }
        return null;
    }

}
