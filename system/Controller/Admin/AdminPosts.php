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
}