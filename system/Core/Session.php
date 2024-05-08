<?php

namespace system\Core;

class Session
{    
    /**
     * __construct session
     *
     * @return void
     */
    public function __construct()
    {
        if(!session_id()){
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
       $_SESSION[$key] = (is_array($value) ? (object) $value: $value);
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
     * clean
     *
     * @param  mixed $key
     * @return Session
     */
    public function clean(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }
    
    /**
     * deleted
     *
     * @return Session
     */
    public function deleted(): Session
    {
        session_destroy();
        return $this;
    }
    
    /**
     * __get
     *
     * @param  mixed $name
     * @return void
     */
    public function __get($name)
    {
        if(!empty($_SESSION[$name])){
            return $_SESSION[$name];
        }
    }
    
    /**
     * flash
     *
     * @return Message
     */
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