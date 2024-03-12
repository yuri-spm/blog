<?php

namespace system\Controller\Admin;

use system\Model\CategoryModel;


class AdminCategories extends AdminController
{
    public function categories()
    {
        echo $this->template->render('categories/categories.html.twig', 
        [
            'categories' =>(new CategoryModel())->find()
        ]);
    }

    public function register()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(isset($dados)){
            (new CategoryModel())->register($data);
        }
       
        echo $this->template->render('categories/forms_categories.html.twig', []);
    }

   
}