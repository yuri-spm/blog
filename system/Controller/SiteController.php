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

    /**
     * Home Page
     * @return void
     */
    public function index(): void
    {
        $posts = (new PostModel())->find("status = 1");
        
        echo $this->template->render('index.html.twig', [
            'posts' => $posts->result(true),
            'categorias' => $this->categorias(),
        ]);
    }
    
    public function findr():void
    {
        $find = filter_input(INPUT_POST,'find', FILTER_DEFAULT);
        if(isset($find)){
            $posts = (new PostModel())->find("status = 1 AND title LIKE '%{$find}%'")->result(true);
            
            foreach ($posts as $post){
                echo "<li class='list-group-item fw-bold'><a href=".Helpers::url('post/').$post->id.">$post->title</a></li>";
            }
        }
        
    }
    
    /**
     * Busca post por ID
     * @param int $id
     * @return void
     */
    public function post(int $id):void
    {
        $post = (new PostModel())->findByID($id);
        if(!$post){
            Helpers::redirect('404');
        }
        
        echo $this->template->render('post.html.twig', [
            'post' => $post,
            'categorias' => $this->categorias(),
        ]);
    }
    
    /**
     * Categorias
     * @return array
     */
    public function categorias(): array
    {
        return (new CategoryModel())->find();
    }

    public function categoria(int $id):void
    {
        $posts = (new CategoryModel())->posts($id);
        
        echo $this->template->render('category.html.twig', [
            'posts' => $posts,
            'categorias' => $this->categorias(),
        ]);
    }
    
    /**
     * Sobre
     * @return void
     */
    public function sobre(): void
    {
        echo $this->template->render('about.html.twig', [
            'title' => 'Sobre nós'
        ]);
    }
    
    /**
     * ERRO 404
     * @return void
     */
    public function error404(): void
    {
        echo $this->template->render('404.html.twig', [
            'title' => 'Página não encontrada'
        ]);
    }

}
