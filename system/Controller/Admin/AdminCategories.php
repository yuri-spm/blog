<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Model\CategoryModel;


class AdminCategories extends AdminController
{
    public function categories_list()
    {
        $categories = new CategoryModel();

        echo $this->template->render(
            'categories/categories.html.twig',
            [
                'categories' => $categories->find(),
                'total'      => [
                    'total'  => $categories->count(),
                    'active'  => $categories->count('status = 1'),
                    'inactive'  => $categories->count('status = 0')
                ]    
            ]
        );
    }

    public function register()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            (new CategoryModel())->register($data);
            $this->message->success('Categoria cadastrada com sucesso')->flash();
            Helpers::redirect('admin/categories/categories');
        }

        echo $this->template->render('categories/forms_categories.html.twig', []);
    }

    public function edit(int $id): void
    {
        $category = (new CategoryModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            (new CategoryModel())->update($data, $id);
            
            $this->message->success('Categoria atualizada com sucesso')->flash();
            Helpers::redirect('admin/categories/categories');
        }

        echo $this->template->render('categories/forms_categories.html.twig', [
            'categories'    => $category
        ]);
    }

    public function delete(int $id): void
    {
        (new CategoryModel())->delete($id);
        $this->message->success('Categoria deletada com sucesso')->flash();
        Helpers::redirect('admin/categories/categories');
    }

}