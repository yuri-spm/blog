<?php

namespace system\Controller;

use system\Core\Controller;
use system\Core\Helpers;
use system\Model\PostModel;

class SiteController extends Controller
{

    public function __construct()
    {
        parent::__construct('templates/site/views');
    }

    public function index(): void
    {
        $posts = (new PostModel())->find();
        echo $this->template->render('index.html.twig', [
            'title' => 'Posts',
            'posts' => $posts
        ]);
    }

    public function about(): void
    {
        echo $this->template->render('about.html.twig', [
            'title' => 'Sobre'
        ]);
    }

    public function post(int $id): void
    {
        $post = (new PostModel())->findByID($id);

        if(!$post){
            Helpers::redirect('404');
        }
        echo $this->template->render('post.html.twig', [
            'title' => $post->title,
            'post' => $post
        ]);
        
    }

    public function error404(): void
    {
        echo $this->template->render('404.html.twig', [
            'title' => 'Pagina não encontrada'
        ]);
    }

}
