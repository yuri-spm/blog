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
      echo $this->template->render('login.html.twig', []); 
    }
}
