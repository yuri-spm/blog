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

    public function create()
    {
        
    }

    public function clean()
    {
        
    }

    public function load()
    {
        
    }

    public function check()
    {
        
    }

    public function deleted()
    {
        
    }
}