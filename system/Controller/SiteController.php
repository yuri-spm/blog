<?php

namespace system\Controller;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use system\Core\Controller;
use system\Core\Helpers;
use system\Model\PostModel;
use system\Model\CategoryModel;
use system\Support\Email;
use system\Support\Pager;

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
            'posts' => [
                'slides' => $posts->order('id DESC')->limit(3)->result(true),
                'posts' => $posts->order('id DESC')->limit(10)->offset(3)->result(true),
                'maisLidos' => (new PostModel())->find("status = 1")->order('views DESC')->limit(5)->result(true),
            ],
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Busca posts 
     * @return void
     */
    public function find(): void
    {
        $find = filter_input(INPUT_POST, 'search', FILTER_DEFAULT);
        if (isset($find)) {
            $posts = (new PostModel())->find("status = 1 AND title LIKE '%{$find}%'")->result(true);
            if ($posts) {
                foreach ($posts as $post) {
                    echo "<li class='list-group-item fw-bold'><a href=" . Helpers::url('post/') . $post->slug . ">$post->title</a></li>";
                }
            }
        }
    }

    /**
     * Busca post por ID
     * @param string $slug
     * @return void
     */
    public function post(string $slug): void
    {
        $post = (new PostModel())->findBySlug($slug);
        if (!$post) {
            Helpers::redirect('404');
        }
        $post->saveViews();

        echo $this->template->render('post.html.twig', [
            'post' => $post,
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Categorias
     * @return array
     */
    public function categories(): array
    {
        return (new CategoryModel())->find("status = 1")->result(true);
    }

    /**
     * Lista posts por categoria
     * @param string $slug
     * @return void
     */
    public function category(string $slug, int $pager = null): void
    {
        $category = (new CategoryModel())->findBySlug($slug);
        if (!$category) {
            Helpers::redirect('404');
        }
        $category->saveViews();
    
        $posts = new PostModel();
        $count = $posts->find('category_id = :c AND status = 1', "c={$category->id} COUNT(id)", 'id')->count();
    
        $pager = new Pager(Helpers::url('category/' . $slug), ($pager ?? 1), 6, 3, $count);
        
        $paginatedPosts = $posts->find('category_id = :c AND status = 1', "c={$category->id}")
            ->limit($pager->limit())
            ->offset($pager->offset())
            ->result(true);

        echo $this->template->render('category.html.twig', [
            'posts' => $paginatedPosts,
            'pageraction' => $pager->render(),
            // 'pageractionInfo' => $pager->info(),
            'categories' => $this->categories(),
        ]);
    }
    

    /**
     * Sobre
     * @return void
     */
    public function about(): void
    {
        echo $this->template->render('about.html.twig', [
            'title' => 'Sobre nós',
            'categories' => $this->categories(),
        ]);
    }

    public function contact():void
    {
        $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        if(isset($data)){
            if(in_array('', $data)){
                Helpers::json('erro', 'Preencha todos os campos!');
                return;
            }elseif(!Helpers::validateEmail($data['email'])){
                Helpers::json('erro', 'Preencha o e-mail corretamente');
                return;
            }else{
                try{
                    $email = new Email();
                   
                    $email->create(
                       'Contato via Site - ' . SITE_NOME,
                        $data['mensagem'],
                        $data['email'],
                        $data['nome'],
                        'yspm.developer@gmail.com',
                        'Helpx',
                       
                    );
                    $attachments = $_FILES['anexos'];

                    foreach($attachments['tmp_name'] as $key => $attachment){
                        if(!$attachment == UPLOAD_ERR_OK){
                            $email->attachment($attachment, $attachments['name'][$key]);
                        }
                    }

                    $email->send(
                        $data['email'],
                        $data['nome'],
                    );
                    $this->message->success('Email enviado com sucesso.')->flash();
                    $json = ['redirect' =>  Helpers::url('/contato')];
                    Helpers::json('redirect', Helpers::url('/contato'));
                    return;
          
                    

                }catch(\PHPMailer\PHPMailer\Exception $ex){
                    $this->message->alert($ex->getMessage())->flash();                    
                }
            }
        }
        echo $this->template->render('contact.html.twig',[
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
