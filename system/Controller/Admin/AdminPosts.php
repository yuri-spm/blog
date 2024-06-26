<?php

namespace system\Controller\Admin;

use FontLib\Table\Type\head;
use system\Core\Helpers;
use system\Core\Message;
use system\Model\CategoryModel;
use system\Model\PostModel;
use system\Support\Upload;

class AdminPosts extends AdminController
{
    private string $cover;
    
    /**
     * lists
     *
     * @return void
     */
    public function lists(): void
    {
        $post = new PostModel(); 

        echo $this->template->render('posts/posts.html.twig', [
            'posts' => $post->find()->order('status ASC, id DESC')->result(true),
            'total' => [
                'posts'         => $post->count(),
                'postsActive'   => $post->find('status = 1')->count(),
                'postsInactive' => $post->find('status = 0')->count(),
                

            ]
        ]);
    }
    
    /**
     * add
     *
     * @return void
     */
    public function add(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(isset($data)){
            if ($this->validateData($data)) {
                $post = (new PostModel());
                
                $post->title = $data['title'];
                $post->category_id = $data['category_id'];
                $post->slug = Helpers::slug($data['title']) .'-'. uniqid();
                $post->text = $data['text'];
                $post->status = $data['status'];
                $post->cover = $this->cover;
    
                if ($post->save()) {
                    $this->message->success('Post atualizado com sucesso')->flash();
                    Helpers::redirect('admin/posts/posts');
                }else{
                    $this->message->error($post->error())->flash();
                    Helpers::redirect('admin/posts/posts');
                }
            }
        }


        echo $this->template->render('posts/forms_posts.html.twig', [
            'categories' =>  (new CategoryModel())->find()->result(true),
            'post' => $data
        ]);
    }
    
    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit(int $id): void
    {
        $post = (new PostModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {

            if ($this->validateData($data)) {
                $post = (new PostModel())->findByID($id);

                $post->title = $data['title'];
                $post->category_id = $data['category_id'];
                $post->slug = Helpers::slug($data['title']) .'-'. uniqid();
                $post->text = $data['text'];
                $post->status = $data['status'];
                $post->update_at = date('Y-m-d H:i:s');

                if(!empty($_FILES['cover'])){
                    if($post->cover && file_exists("uploads/files/{$post->cover}")){
                        unlink("uploads/files/{$post->cover}");
                    }
                    $post->cover = $this->cover;
                }


                if ($post->save()) {
                    $this->message->success('Post atualizado com sucesso')->flash();
                    Helpers::redirect('admin/posts/posts');
                } else {
                    $this->message->error($post->error())->flash();
                    Helpers::redirect('admin/posts/posts');
                }
            }
        }

        echo $this->template->render('posts/forms_posts.html.twig', [
            'post' => $post,
            'categories' => (new CategoryModel())->find()->result(true)
        ]);
    }



    
    /**
     * validateData
     *
     * @param  mixed $data
     * @return bool
     */
    public function validateData(array $data): bool
    {
        if(!empty($_FILES['cover'])){
            $upload = new Upload();
            $upload->file($_FILES['cover'], Helpers::slug($data['title'], 'images'));
            if($upload->getResult()){
                $this->cover = $upload->getResult();
            }else{
                $this->message->alert($upload->getError())->flash();
            }
        }
        if (empty($data['title'])) {
            $this->message->alert('Escreva um título para o Post!')->flash();
            return false;
        }
        if (empty($data['text'])) {
            $this->message->alert('Escreva um texto para o Post!')->flash();
            return false;
        }

        return true;
    }

    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete(int $id): void
    {
        if (is_int($id)) {
            $post = (new PostModel())->findByID($id);
            if (!$post) {
                $this->message->alert('O post que você está tentando deletar não existe!')->flash();
                Helpers::redirect('admin/posts/posts');
            } else {
                if($post->destroy()){
                        if($post->cover && file_exists("uploads/files/{$post->cover}")){
                            unlink("uploads/files/{$post->cover}");
                        }
                    $this->message->success('Post deletado com sucesso!')->flash();
                Helpers::redirect('admin/posts/posts');
                }else {
                    $this->message->error($post->error())->flash();
                Helpers::redirect('admin/posts/posts');
                }
                
                
            }
        }
    }

}
