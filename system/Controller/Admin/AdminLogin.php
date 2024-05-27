<?php


namespace system\Controller\Admin;

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
     
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if (isset($data)) {
        if (in_array('', $data)) {
          $this->message->alert("Todos os campos são obrigatorios")->flash();
        }else{
          $user = (new UserModel())->login($data, 3);
          if ($user){
            Helpers::redirect('admin/login');
          }
        }
      }
      echo $this->template->render("login.html.twig",[]);
    }
}
