<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Core\Message;
use system\Model\CategoryModel;
use system\Model\PostModel;

class AdminPosts extends AdminController
{

    public function lists(): void
    {
        $post = new PostModel();

        echo $this->template->render('admin/posts/posts', [
            'posts' => $post->find()->order('status ASC, id DESC')->result(true),
            'count' => [
                'posts' => $post->count(),
                'active'   => $post->find('status = 1')->count(),
                'inactive' => $post->find('status = 0')->count()
            ]
        ]);
    }

    public function register(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {

            $post = new PostModel();

            $post->titulo = $dados['title'];
            $post->categoria_id = $dados['category_id'];
            $post->texto = $dados['text'];
            $post->status = $dados['status'];

            if ($post->save()) {
                $this->message->success('Post cadastrado com sucesso')->flash();
                Helpers::redirect('admin/posts/posts');
            }
        }

        echo $this->template->render('posts/forms_posts.html.twig', [
            'categorias' => (new CategoryModel())->find()
        ]);
    }

    public function edit(int $id): void
    {
        $post = (new PostModel())->findByID($id);

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {

            $post = (new PostModel())->findByID($id);

            $post->titulo = $dados['titulo'];
            $post->categoria_id = $dados['categoria_id'];
            $post->texto = $dados['texto'];
            $post->status = $dados['status'];

            if ($post->salvar()) {
                $this->message->success('Post atualizado com sucesso')->flash();
                Helpers::redirect('admin/posts/listar');
            }
        }

        echo $this->template->render('posts/forms_posts.html.twig', [
            'post' => $post,
            'categorias' => (new CategoryModel())->find()
        ]);
    }

    public function deletar(int $id): void
    {
//        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (is_int($id)) {
            $post = (new PostModel())->findByID($id);
            if (!$post) {
                $this->message->alert('O post que você está tentando deletar não existe!')->flash();
                Helpers::redirect('admin/posts/posts'); 
            } else {
                if($post->deletar()){
                    $this->message->success('Post deletado com sucesso!')->flash();
                    Helpers::redirect('admin/posts/posts'); 
                }else {
                    $this->message->error($post->erro())->flash();
                    Helpers::redirect('admin/posts/posts'); 
                }
                
                
            }
        }
    }

}
