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
     * Home do admin
     * @return void
     */
    public function dashboard(): void
    {
        $posts = new PostModel();
        $users = new UserModel();
        $categorias = new CategoryModel();

        echo $this->template->render('dashboard.html.twig', [
            'posts' => [
                'posts' => $posts->find()->order('id DESC')->limit(5)->result(true),
                'count' => $posts->find(null,'COUNT(id)','id')->count(),
                'ativo' => $posts->find('status = :s','s=1 COUNT(status)','status')->count(),
                'inativo' => $posts->find('status = :s','s=0 COUNT(status)','status')->count()
            ],
            'categorias' => [
                'categorias' => $categorias->find()->order('id DESC')->limit(5)->result(true),
                'count' => $categorias->find()->count(),
                'categoriasAtiva' => $categorias->find('status = 1')->count(),
                'categoriasInativa' => $categorias->find('status = 0')->count(),
            ],
            'users' => [
                'logins' => $users->find()->order('last_login DESC')->limit(5)->result(true),
                'users' => $users->find('level != 3')->count(),
                'usersAtivo' => $users->find('status = 1 AND level != 3')->count(),
                'usersInativo' => $users->find('status = 0 AND level != 3')->count(),
                'admin' => $users->find('level = 3')->count(),
                'adminAtivo' => $users->find('status = 1 AND level = 3')->count(),
                'adminInativo' => $users->find('status = 0 AND level = 3')->count()
            ],
        ]);
    }

    /**
     * Faz logout do usuário
     * @return void
     */
    public function exit(): void
    {
        $sessao = new Session();
        $sessao->clear('userId');

        $this->message->info('Você saiu do painel de controle!')->flash();
        Helpers::redirect('admin/login');
    }

}
