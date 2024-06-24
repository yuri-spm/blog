<?php

namespace system\Controller;

use system\Core\Controller;
use system\Core\Helpers;
use system\Model\PostModel;
use system\Model\CategoryModel;

class SiteController extends Controller
{
    
    /**
     * __construct
     *
     * @return void
     */
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
            'categories' => $this->categories(),
        ]);
    }

    public function find(): void
    {
        $find = filter_input(INPUT_POST, 'search', FILTER_DEFAULT);
        if (isset($find)) {
            $posts = (new PostModel())->find("status = 1 AND title LIKE '%{$find}%'")->result(true);
            if ($posts) {
                foreach ($posts as $post) {
                    echo "<li class='list-group-item fw-bold'><a href=" . Helpers::url('post/') . $post->id . ">$post->title</a></li>";
                }
            }
        }
    }

      
    /**
     * post
     *
     * @param  mixed $slug
     * @return void
     */
    public function post(string $slug): void
    {
        $post = (new PostModel())->findBySlug($slug);
        if (!$post) {
            Helpers::redirect('404');
        }
        $post->views = $post->views + 1;
        $post->last_views = date('Y-m-d H:i:s');
        $post->save();

        echo $this->template->render('post.html.twig', [
            'post' => $post,
            'categories' => $this->categories(),
        ]);
    }

      
    /**
     * categories
     *
     * @return array
     */
    public function categories(): array
    {
        return (new CategoryModel())->find("status = 1")->result(true);
    }
    
    /**
     * category
     *
     * @param  mixed $slug
     * @return void
     */
    public function category(string $slug): void
    {
        $category = (new CategoryModel())->findBySlug($slug);
        if (!$category) {
            Helpers::redirect('404');
        }



        echo $this->template->render('category.html.twig', [
            'posts' => (new CategoryModel)->posts($category->id),
            'categories' => $this->categories(),
        ]);
    }

      
    /**
     * about
     *
     * @return void
     */
    public function about(): void
    {
        echo $this->template->render('about.html.twig', [
            'title' => 'Sobre nós',
            'categories' => $this->categories(),
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
