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
                'categories' => $categories->all(),
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

        echo $this->template->render('categorias/formulario.html', []);
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

        echo $this->template->render('categorias/formulario.html', [
            'categories'    => $category
        ]);
    }

    public function destroy(int $id): void
    {
        (new CategoryModel())->delete($id);
        $this->message->success('Categoria deletada com sucesso')->flash();
        Helpers::redirect('admin/categories/categories');
    }

}
