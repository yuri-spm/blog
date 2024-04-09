<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Core\Message;
use system\Model\CategoryModel;
use system\Model\PostModel;

class AdminPosts extends AdminController
{
    public function lists()
    {
        echo $this->template->render(
            'posts/posts.html.twig',
            [
                'posts' => (new PostModel())->all()
            ]
        );
    }

    public function register()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($data)) {
            (new PostModel())->register($data);
            Helpers::redirect('admin/posts/posts');
        }

        echo $this->template->render(
            'posts/forms_posts.html.twig',
            [
                'categories' => (new CategoryModel())->all()
            ]
        );
    }

    public function edit($id)
    {
        $post = (new PostModel())->findByID($id); 

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       
        if(isset($data)){
            var_dump($data);
            die();
            (new PostModel())->update($data, $id); 
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
}
