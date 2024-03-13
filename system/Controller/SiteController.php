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
        $posts = (new PostModel())->find();
        // var_dump($category, $posts);
        // die();
        echo $this->template->render('index.html.twig', [
            'title' => 'Posts',
            'posts' => $posts,
            'categories' => $this->categories(),
        ]);
    }

    public function search(): void
    {
        $search = filter_input(INPUT_POST,'search', FILTER_DEFAULT);
        if(isset($search)){
            $posts = (new PostModel())->search($search);
            
            foreach ($posts as $post){
                echo "<li class='list-group-item fw-bold'><a href=".Helpers::url('post/').$post->id.">$post->title</a></li>";
            }
        }
       
    }

    public function about(): void
    {
        echo $this->template->render('about.html.twig', [
            'title' => 'Sobre',
            'categories' => $this->categories(),
        ]);
    }

    public function post(int $id): void
    {
        $posts = (new PostModel())->findByID($id);

        if(!$posts){
            Helpers::redirect('404');
        }
        echo $this->template->render('post.html.twig', [
            'title' => $posts->title,
            'post' => $posts,
            'categories' => $this->categories()
        ]);
        
    }

    public function categories(): array
    {
        return (new CategoryModel())->find();
    }

    public function category(int $id): void
    {
        $posts = (new CategoryModel())->posts($id);
        echo $this->template->render('category.html.twig', [
            'posts' => $posts,
            'categories' => $this->categories(),
        ]);
    }

    public function error404(): void
    {
        echo $this->template->render('404.html.twig', [
            'title' => 'Pagina não encontrada'
        ]);
    }

}
