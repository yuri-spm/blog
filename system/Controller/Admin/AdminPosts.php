<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Core\Message;
use system\Model\CategoryModel;
use system\Model\PostModel;

class AdminPosts extends AdminController
{    
    /**
     * lists
     *
     * @return void
     */
    public function lists()
    {
        $post = new PostModel();
        echo $this->template->render(
            'posts/posts.html.twig',
            [
                'posts' => $post->find()->order('id DESC')->result(true),
                'total' => [
                    'total'   => $post->count(),
                    'ativo'   => $post->count('status = 1'),
                    'inativo' => $post->count('status = 0'),
                ]
            ]
        );
    }
    
    /**
     * register
     *
     * @return void
     */
    public function register()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($data)) {
            (new PostModel())->register($data);
            $this->message->success('Post cadastrado com sucesso')->flash();
            Helpers::redirect('admin/posts/posts');
        }

        echo $this->template->render(
            'posts/forms_posts.html.twig',
            [
                'categories' => (new CategoryModel())->all()
            ]
        );
    }
    
    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        $post = (new PostModel())->findByID($id); 

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       
        if(isset($data)){
            (new PostModel())->update($data, $id); 
            $this->message->success('Post editada com sucesso')->flash();
            Helpers::redirect('admin/posts/posts'); 
        }
        echo $this->template->render(
                'posts/forms_posts.html.twig',
                [
                    'post'          => $post,  
                    'categories'    => (new CategoryModel())->find(),                
                ]
            );
    }
    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        (new PostModel())->delete($id);
        $this->message->success('Post deletado com sucesso')->flash();
        Helpers::redirect('admin/posts/posts'); 
        Helpers::redirect('admin/posts/posts');
    }
}
