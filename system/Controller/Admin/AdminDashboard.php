<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Core\Session;
use system\Model\CategoryModel;
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
       $categories = new CategoryModel();
       echo $this->template->render('dashboard.html.twig',[
        'posts' => [
            'posts' => $post->find()->order('id DESC')->limit(5)->result(true),
            'total'      => $post->find()->count(),
            'active'     => $post->find('status = 1')->count(),
            'inactive'   => $post->find('status = 0')->count(),
        ],
        'users' => [
            'logins'         => $user->find()->order('last_login DESC')->limit(5)->result(true),
            'users'          => $user->find('level != 3')->count(),
            'users_active'   => $user->find('status = 1 AND level !=3')->count(),
            'users_inactive' => $user->find('status = 0 AND level !=3')->count(),
            'admin'          => $user->find('level = 3')->count(),
            'admin_active'          => $user->find('status = 1 AND level = 3')->count(),
            'admin_inactive' => $user->find('status = 0 AND level =3')->count()
        ],
        'categories' => [
            'total'    => $categories->find()->count(),
            'active'   => $categories->find('status = 1')->count(),
            'inactive' => $categories->find('status = 0')->count()

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
        $session->clear('userId');

        $this->message->inform('Você saiu do painel de controle')->flash();

        Helpers::redirect('admin/login');
    }
}