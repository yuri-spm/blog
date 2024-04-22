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
}