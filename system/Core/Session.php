<?php

namespace system\Core;

class Session
{
    public function __construct()
    {
        if(!session_id()){
          session_start();
        }   
    }

    public function create(string $key, mixed $value): Session
    {
       $_SESSION[$key] = (is_array($value) ? (object) $value: $value);
       return $this;
    }

    public function load(): ?object
    {
        return (object) $_SESSION;
    }

    public function check(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function clean(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    public function deleted(): Session
    {
        session_destroy();
        return $this;
    }

    public function __get($name)
    {
        if(!empty($_SESSION[$name])){
            return $_SESSION[$name];
        }
    }

    public function flash(): ?Message
    {
        if($this->check('flash')){
            $flash = $this->flash;
            $this->clean('flash');
            return $flash;
        }
        return null;
    }
}