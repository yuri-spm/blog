<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Model\CategoryModel;


class AdminCategories extends AdminController
{
    public function categories()
    {
        echo $this->template->render('categories/categories.html.twig', 
        [
            'categories' =>(new CategoryModel())->all()
        ]);
    }

    public function register()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(isset($data)){
            (new CategoryModel())->register($data);
            Helpers::redirect('admin/categories/categories');
        }
       
        echo $this->template->render('categories/forms_categories.html.twig', []);
    }

   
}