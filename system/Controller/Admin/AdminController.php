<?php


namespace system\Controller\Admin;

use system\Controller\UserController;
use system\Core\Controller;
use system\Core\Helpers;
use system\Core\Session;
use system\Model\UserModel;

class AdminController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct('templates/admin/views');

        $this->user = UserController::user();

        if(!$this->user OR $this->user->level != 3){
            $this->message->error("Por favor faça login")->flash();

            (new Session())->clean('userId');

            Helpers::redirect("admin/login");
        }
    }      
    
}
