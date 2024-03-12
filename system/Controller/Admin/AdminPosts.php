<?php

namespace system\Controller\Admin;

use system\Model\PostModel;

class AdminPosts extends AdminController
{
    public function lists()
    {
        echo $this->template->render('posts/lists.html.twig', 
        [
            'posts' =>(new PostModel())->find()
        ]);
    }

    public function register()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(isset($data)){
            
        }
        echo $this->template->render('posts/forms_posts.html.twig', []);
    }
}