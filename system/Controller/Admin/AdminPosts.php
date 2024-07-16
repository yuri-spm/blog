<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Model\CategoryModel;
use system\Model\PostModel;
use Verot\Upload\Upload;

class AdminPosts extends AdminController
{

    private string $cover;

    public function datatable(): void
    {
        $dataTable = $_REQUEST;
        $dataTable = filter_var_array($dataTable, FILTER_SANITIZE_SPECIAL_CHARS);

        $limit = $dataTable['length'];
        $offset = $dataTable['start'];
        $find = $dataTable['search']['value'];
        $columms = [
            0 => 'id',
            1 => 'title'
        ];

        $order = " ".$columms[$dataTable['order'][0]['column']]." ";
        $order .= " ".$dataTable['order'][0]['dir']. " ";

        

        $posts = new PostModel();

        if (empty($find)) {
            $posts->find()->order($order)->limit($limit)->offset($offset);
            $total = (new PostModel())->find(null, 'COUNT(id)', 'id')->count();
        } else {
            $posts->find("id LIKE '%{$find}%' OR title LIKE '%{$find}%' ")->limit($limit)->offset($offset);
            $total = $posts->count();
        }

        $data = [];

        foreach ($posts->result(true) as $post) {
            $data[] = [
                $post->id,
                $post->title
            ];
        }

        $return = [
            "draw" => $dataTable['draw'],
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        ];

        echo json_encode($return);
    }
    

    /**
     * Lista posts
     * @return void
     */
    public function lists(): void
    {
        $post = new PostModel();

        echo $this->template->render('posts/listar.html.twig', [
            // 'posts' => $post->find()->order('status ASC, id DESC')->result(true),
            'count' => [
                'posts' => 0,
                'postsAtivo' => 0,
                'postsInativo' => 0,
            ]
        ]);
    }

    /**
     * Cadastra posts
     * @return void
     */
    public function add(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {

            if ($this->validateData($data)) {
                $post = new PostModel();

                $post->user_id = $this->user->id;
                $post->category_id = $data['category_id'];
                $post->slug = Helpers::slug($data['title']);
                $post->title = $data['title'];
                $post->text = $data['text'];
                $post->status = $data['status'];
                $post->cover = $this->cover ?? null;

                if ($post->save()) {
                    $this->message->success('Post cadastrado com success')->flash();
                    Helpers::redirect('admin/posts/listar');
                } else {
                    $this->message->error($post->error())->flash();
                    Helpers::redirect('admin/posts/lists');
                }
            }
        }

        echo $this->template->render('posts/formulario.html.twig', [
            'categorias' => (new CategoryModel())->find('status = 1')->result(true),
            'post' => $data
        ]);
    }

    /**
     * Edita post pelo ID
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        $post = (new PostModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {

            if ($this->validateData($data)) {
                $post = (new PostModel())->findByID($id);

                $post->user_id = $this->user->id;
                $post->category_id = $data['category_id'];
                $post->slug = Helpers::slug($data['title']);
                $post->title = $data['title'];
                $post->text = $data['text'];
                $post->status = $data['status'];
                $post->update_at = date('Y-m-d H:i:s');

                if (!empty($_FILES['cover'])) {
                    if ($post->cover && file_exists("uploads/imagens/{$post->cover}")) {
                        unlink("uploads/imagens/{$post->cover}");
                        unlink("uploads/imagens/thumbs/{$post->cover}");
                    }
                    $post->cover = $this->cover ?? null;
                }

                if ($post->save()) {
                    $this->message->success('Post atualizado com success')->flash();
                    Helpers::redirect('admin/posts/lists');
                } else {
                    $this->message->error($post->error())->flash();
                    Helpers::redirect('admin/posts/lists');
                }
            }
        }

        echo $this->template->render('posts/formulario.html.twig', [
            'post' => $post,
            'categorias' => (new CategoryModel())->find('status = 1')->result(true)
        ]);
    }

    /**
     * Valida os data do formulário
     * @param array $data
     * @return bool
     */
    public function validateData(array $data): bool
    {

        if (empty($data['title'])) {
            $this->message->alert('Escreva um título para o Post!')->flash();
            return false;
        }
        if (empty($data['text'])) {
            $this->message->alert('Escreva um text para o Post!')->flash();
            return false;
        }

        if (!empty($_FILES['cover'])) {
            $upload = new Upload($_FILES['cover'], 'pt_BR');
            if ($upload->uploaded) {
                $titulo = $upload->file_new_name_body = Helpers::slug($data['title']);
                $upload->jpeg_quality = 90;
                $upload->image_convert = 'jpg';
                $upload->process('uploads/imagens/');

                if ($upload->processed) {
                    $this->cover = $upload->file_dst_name;
                    $upload->file_new_name_body = $titulo;
                    $upload->image_resize = true;
                    $upload->image_x = 540;
                    $upload->image_y = 304;
                    $upload->jpeg_quality = 70;
                    $upload->image_convert = 'jpg';
                    $upload->process('uploads/imagens/thumbs/');
                    $upload->clean();
                } else {
                    $this->message->alert($upload->error)->flash();
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Deleta posts por ID
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        if (is_int($id)) {
            $post = (new PostModel())->findByID($id);
            if (!$post) {
                $this->message->alert('O post que você está tentando deletar não existe!')->flash();
                Helpers::redirect('admin/posts/listar');
            } else {
                if ($post->beforeDelete()) {

                    if ($post->cover && file_exists("uploads/imagens/{$post->cover}")) {
                        unlink("uploads/imagens/{$post->cover}");
                        unlink("uploads/imagens/thumbs/{$post->cover}");
                    }

                    $this->message->success('Post deletado com success!')->flash();
                    Helpers::redirect('admin/posts/listar');
                } else {
                    $this->message->error($post->error())->flash();
                    Helpers::redirect('admin/posts/lists');
                }
            }
        }
    }

}
