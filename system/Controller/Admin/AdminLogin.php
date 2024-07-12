<?php


namespace system\Controller\Admin;

use system\Controller\UserController;
use system\Core\Helpers;
use system\Core\Controller;
use system\Model\UserModel;

class AdminLogin extends Controller
{
   

    public function __construct()
    {
        parent::__construct('templates/admin/views');
    }

    public function login(): void
    {
        $user = UserController::user();
        if($user && $user->level == 3){
            Helpers::redirect('admin/dashboard');
        }
        
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if (in_array('', $data)) {
                $this->message->alert('Todos os campos são obrigatórios!')->flash();
            } else {
                $user = (new UserModel())->login($data, 3);
                if($user){
                    Helpers::redirect('admin/login');
                }
            }
        }

        echo $this->template->render('login.html.twig', []);
    }

}
