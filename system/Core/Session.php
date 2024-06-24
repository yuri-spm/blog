<?php

namespace system\Core;

class Session
{
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        //checa se não existe um ID de sessão
        if (!session_id()) {
            //inicia uma nova sessão ou resume uma sessão existente
            session_start();
        }
    }

    
    /**
     * create
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return Session
     */
    public function create(string $key, mixed $value): Session
    {
        $_SESSION[$key] = (is_array($value) ? (object) $value : $value);
        return $this;
    }

    
    /**
     * load
     *
     * @return object
     */
    public function load(): ?object
    {
        return (object) $_SESSION;
    }

    
    /**
     * check
     *
     * @param  mixed $key
     * @return bool
     */
    public function check(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    
    /**
     * clear
     *
     * @param  mixed $key
     * @return Session
     */
    public function clear(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    
    /**
     * destroy
     *
     * @return Session
     */
    public function destroy(): Session
    {
        session_destroy();
        return $this;
    }

    
    /**
     * __get
     *
     * @param  mixed $atributo
     * @return void
     */
    public function __get($atributo)
    {
        if (!empty($_SESSION[$atributo])) {
            return $_SESSION[$atributo];
        }
    }

    
    /**
     * flash
     *
     * @return Message
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
