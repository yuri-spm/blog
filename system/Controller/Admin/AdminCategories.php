<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Model\CategoryModel;
use system\Model\PostModel;

class AdminCategories extends AdminController
{    
    /**
     * lists
     *
     * @return void
     */
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
    
    /**
     * modalCategories
     *
     * @return void
     */
    public function modalCategories()
    {
        echo $this->template->render(
            'categories/modal_categories.html.twig',[]
        );
    }
    
    /**
     * add
     *
     * @return void
     */
    public function add()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $category = new CategoryModel();

                $category->title = $data['title'];
                $category->text  = $data['text'];
                $category->slug  = Helpers::slug($data['title']) .'-'. uniqid();
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
    
    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit(int $id): void
    {
        $category = (new CategoryModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $category->title = $data['title'];
                $category->text  = $data['text'];
                $category->slug  = Helpers::slug($data['title']) .'-'. uniqid();
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
    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
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
    
    /**
     * validateData
     *
     * @param  mixed $data
     * @return bool
     */
    private function validateData(array $data): bool
    {
        if (empty($data['title'])) {
            $this->message->alert('Escreva um título para o categoria!')->flash();
            return false;
        }

        return true;
    }
}
