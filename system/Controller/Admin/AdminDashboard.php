<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Core\Session;

class AdminDashboard extends AdminController
{    
    /**
     * dashboard
     *
     * @return void
     */
    public function dashboard():void
    {
       echo $this->template->render('dashboard.html.twig',[]);
    }
    
    /**
     * exit
     *
     * @return void
     */
    public function exit()
    {
        $session = new Session();
        $session->clean('userId');

        $this->message->info('Você saiu do painel de controle')->flash();

        Helpers::redirect('admin/login');
    }
}