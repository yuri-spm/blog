<?php

namespace system\Controller;

use system\Core\Controller;
use system\Core\Helpers;
use system\Model\PostModel;
use system\Model\CategoryModel;

class SiteController extends Controller
{

    public function __construct()
    {
        parent::__construct('templates/site/views');
    }

    public function index(): void
    {
        $category = (new CategoryModel())->find();
       
        $posts = (new PostModel())->find();
        // var_dump($category, $posts);
        // die();
        echo $this->template->render('index.html.twig', [
            'title' => 'Posts',
            'posts' => $posts,
            'categories' => $this->category(),
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

    public function category(): array
    {
        return (new CategoryModel())->find();
    }

    public function error404(): void
    {
        echo $this->template->render('404.html.twig', [
            'title' => 'Pagina não encontrada'
        ]);
    }

}
