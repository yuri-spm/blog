<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Model\CategoryModel;
use system\Model\PostModel;

class AdminCategories extends AdminController
{
    public function lists()
    {
        $categories = new CategoryModel();

        echo $this->template->render(
            'categories/categories.html.twig',
            [
                'categories' => $categories->find()->order('title ASC')->result(true),
                'total'      => [
                    'total'  => $categories->find()->count(),
                    'active'  => $categories->find('status = 1')->count(),
                    'inactive'  => $categories->find('status = 0')->count()
                ]
            ]
        );
    }

    public function modalCategories()
    {
        echo $this->template->render(
            'categories/modal_categories.html.twig',[]
        );
    }

    public function add()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $category = new CategoryModel();

                $category->title = $data['title'];
                $category->text  = $data['text'];
                $category->status  = $data['status'];

                if ($category->save()) {
                    $this->message->success('Categoria cadastrada com sucesso')->flash();
                    Helpers::redirect('admin/categories/categories');
                } else {
                    $this->message->error($category->error())->flash();
                    Helpers::redirect('admin/categories/categories');
                }
            }
        }

        echo $this->template->render('categories/forms_categories.html.twig', []);
    }

    public function edit(int $id): void
    {
        $category = (new CategoryModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $category->title = $data['title'];
                $category->text  = $data['text'];
                $category->status  = $data['status'];
                $category->update_at = date('Y-m-d H:i:s');

                if ($category->save()) {
                    $this->message->success('Categoria cadastrada com sucesso')->flash();
                    Helpers::redirect('admin/categories/categories');
                } else {
                    $this->message->error($category->error())->flash();
                    Helpers::redirect('admin/categories/categories');
                }
            }
        }

        echo $this->template->render('categories/forms_categories.html.twig', [
            'categories'    => $category
        ]);
    }

    public function delete(int $id): void
    {
        if (is_int($id)) {
            $category = (new CategoryModel())->findByID($id);

            // $posts = (new PostModel())->find('category_id = {$category->id}')->result();

            if (!$category) {
                $this->message->alert('A categoria que você esta tentando deletar não existe')->flash();
                Helpers::redirect('admin/categories/categories');
            }elseif($category->posts($category->id)){
                $this->message->alert("A categoria {$category->title } possui posts vinculados")->flash();
                Helpers::redirect('admin/categories/categories');
            }else {
                if ($category->destroy()) {
                    $this->message->success('Categoria deletada com sucesso!')->flash();
                    Helpers::redirect('admin/categories/categories');
                } else {
                    $this->message->error($category->error())->flash();
                    Helpers::redirect('admin/categories/categories');
                }
            }
        }
    }

    private function validateData(array $data): bool
    {
        if (empty($data['title'])) {
            $this->message->alert('Escreva um título para o categoria!')->flash();
            return false;
        }

        return true;
    }
}
