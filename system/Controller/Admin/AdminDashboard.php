<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Core\Session;
use system\Model\PostModel;
use system\Model\UserModel;

class AdminDashboard extends AdminController
{    
    /**
     * dashboard
     *
     * @return void
     */
    public function dashboard():void
    {
       $post = new PostModel();
       $user = new UserModel();
       echo $this->template->render('dashboard.html.twig',[
        'posts' => [
            'total'  => $post->find()->count(),
            'active' => $post->find('status = 1')->count(),
            'inactive' => $post->find('status = 0')->count(),
        ],
        'users' => [
            'total'  => $user->find()->count(),
            'active' => $user->find('status = 1')->count(),
            'inactive' => $user->find('status = 0')->count(),
        ]
       ]);
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