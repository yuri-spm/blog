<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Model\CategoryModel;


class AdminCategories extends AdminController
{
    public function categories_list()
    {
        echo $this->template->render(
            'categories/categories.html.twig',
            [
                'categories' => (new CategoryModel())->all()
            ]
        );
    }

    public function register()
    {

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            (new CategoryModel())->register($data);
            Helpers::redirect('admin/categories/categories');
        }

        echo $this->template->render('categories/forms_categories.html.twig', []);
    }

    // public function edit(int $id):void
    // {
       
    //     $category = (new CategoryModel())->findByID($id);
    //     var_dump($category);
    //     die();
    //     $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
      
    //     if(isset($data)){
    //         var_dump($data);
    //         die();
    //          (new CategoryModel())->update($data, $id);
    //          Helpers::redirect('admin/categories/forms_categories.html.twig');
    //     }
        
    //     echo $this->template->render('categories/forms_categories.html.twig', [
    //         'categories' => $category
    //     ]);
    // }

    public function edit($id)
    {
        $category = (new CategoryModel())->findByID($id); 

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
       
        if(isset($data)){
            (new CategoryModel())->update($data, $id);
            Helpers::redirect('admin/categories/categories'); 
        }
        echo $this->template->render(
                'categories/forms_categories.html.twig',
                [
                    
                    'categories'    => $category            
                ]
            );
    }
}

