<?php


namespace system\Controller\Admin;

use system\Core\Controller;
use system\Core\Helpers;
class AdminLogin extends Controller
{
    public function __construct()
    {
        parent::__construct('templates/admin/views');

    }      
    
    public function login(): void
    {
      $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      if(isset($data)){
        if($this->checkData($data)){
          $this->message->success('dados validados')->flash();
        }
      }
      echo $this->template->render('login.html.twig', []); 
    }
    
    private function checkData(array $data): bool
    {
      if(isset($data)){
        if(in_array('', $data)){
          $this->message->alert('Todos os campos são obrigatorios')->flash();
          return false;
        }
      }
      return true;
    }
}
