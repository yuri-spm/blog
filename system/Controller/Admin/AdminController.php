<?php


namespace system\Controller\Admin;

use system\Core\Controller;
use system\Core\Helpers;
class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct('templates/admin/views');

        $user = false;

        if(!$user){
            $this->message->error("Por favor faça login")->flash();
            Helpers::redirect("admin/login");

        }
    }      
    
}
