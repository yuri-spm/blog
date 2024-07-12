<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Model\CategoryModel;
use system\Model\PostModel;

class AdminCategories extends AdminController
{    

    /**
     * Lista categorias
     * @return void
     */
    public function lists(): void
    {
        $categorias = new CategoryModel();

        echo $this->template->render('categories/listar.html.twig', [
            'categorias' => $categorias->find()->order('title ASC')->result(true),
            'count' => [
                'categorias' => $categorias->count(),
                'categoriasAtiva' => $categorias->find('status = 1')->count(),
                'categoriasInativa' => $categorias->find('status = 0')->count(),
            ]
        ]);
    }

    /**
     * Cadastra uma categoria
     * @return void
     */
    public function add(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $categoria = new CategoryModel();

                $categoria->user_id = $this->user->id;
                $categoria->slug = Helpers::slug($data['title']);
                $categoria->title = $data['title'];
                $categoria->text = $data['text'];
                $categoria->status = $data['status'];

                if ($categoria->save()) {
                    $this->message->success('Categoria cadastrada com success')->flash();
                    Helpers::redirect('admin/categories/lists');
                } else {
                    $this->message->error($categoria->error())->flash();
                    Helpers::redirect('admin/categories/lists');
                }
            }
        }

        echo $this->template->render('categories/formulario.html.twig', [
            'categoria' => $data
        ]);
    }

    /**
     * Edita uma categoria pelo ID
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        $categoria = (new CategoryModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $categoria = (new CategoryModel())->findByID($categoria->id);

                $categoria->user_id = $this->user->id;
                $categoria->slug = Helpers::slug($data['title']);
                $categoria->title = $data['title'];
                $categoria->text = $data['text'];
                $categoria->status = $data['status'];
                $categoria->update_at = date('Y-m-d H:i:s');

                if ($categoria->save()) {
                    $this->message->success('Categoria atualizada com success')->flash();
                    Helpers::redirect('admin/categories/lists');
                } else {
                    $this->message->error($categoria->error())->flash();
                    Helpers::redirect('admin/categories/lists');
                }
            }
        }

        echo $this->template->render('categories/formulario.html.twig', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Valida os data do formulário
     * @param array $data
     * @return bool
     */
    private function validateData(array $data): bool
    {
        if (empty($data['title'])) {
            $this->message->alert('Escreva um título para a Categoria!')->flash();
            return false;
        }
        return true;
    }

    /**
     * Deleta uma categoria pelo ID
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        if (is_int($id)) {
            $categoria = (new CategoryModel())->findByID($id);

            if (!$categoria) {
                $this->message->alert('O categoria que você está tentando deletar não existe!')->flash();
                Helpers::redirect('admin/categories/lists');
            } elseif ($categoria->posts($categoria->id)) {
                $this->message->alert("A categoria {$categoria->title} tem posts cadastrados, delete ou altere os posts antes de deletar!")->flash();
                Helpers::redirect('admin/categories/lists');
            } else {
                if ($categoria->beforeDelete()) {
                    $this->message->success('Categoria deletada com successo!')->flash();
                    Helpers::redirect('admin/categories/lists');
                } else {
                    $this->message->error($categoria->error())->flash();
                    Helpers::redirect('admin/categories/lists');
                }
            }
        }
    }

}
