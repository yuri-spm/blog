<?php

namespace system\Core;
class Controller
{    
    /**
     * __construct
     *
     * @param  mixed $tema
     * @return void
     */
    public function __construct(string $tema = null)
    {
      echo $tema;      
    }
}