<?php

namespace system\Controller\Admin;

class AdminDashboard extends AdminController
{
    public function dashboard():void
    {
       echo $this->template->render('dashboard.html.twig',
    [
        
    ]);
    }
}