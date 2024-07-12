<?php


namespace system\Controller\Admin;

use system\Controller\UserController;
use system\Core\Controller;
use system\Core\Helpers;
use system\Core\Session;


class AdminController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct('templates/admin/views');
        
        $this->user = UserController::user();
        
        if(!$this->user OR $this->user->level != 3){
            $this->message->error('Faça login para acessar o painel de controle!')->flash();
            
            $sessao = new Session();
            $sessao->clear('userId');
            
            Helpers::redirect('admin/login');
        }
    }
    
}
