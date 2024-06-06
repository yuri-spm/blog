<?php

namespace system\Controller\Admin;

use FontLib\EOT\Header;
use system\Core\Helpers;
use system\Model\UserModel;

class AdminUser extends AdminController
{    
    /**
     * lists
     *
     * @return void
     */
    public function lists()
    {
        $users = new UserModel();
        echo $this->template->render('users/users.html.twig', [
            'users' => $users->find()->order('level DESC, status ASC')->result(true),
            'total' => [
                'users'         => $users->find('status = 1 AND level != 3')->count(),
                'userActive'    => $users->find('status = 1 AND level != 3')->count(),
                'userInactive'  => $users->find('status = 0 AND level != 3')->count(),
                'admin'         => $users->find('level = 3')->count(),
                'adminActive'   => $users->find('status = 1 AND level = 3')->count(),
                'adminInactive' => $users->find('status = 0 AND level = 3')->count()

            ]    
        ]);
    }

    
    /**
     * register user
     *
     * @return void
     */
    public function register()
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($data)){
            if($this->validateData($data)){
                if(empty($data['password'])){
                    $this->message->alert('Informe uma senha para o usuário')->flash();
                }else{
                    $user = new UserModel();

                    $user->name     = $data['name'];
                    $user->email    = $data['email'];
                    $user->password = $data['password'];
                    $user->level    = $data['level'];
                    $user->status   = $data['status'];

                    if($user->save()){
                        $this->message->success('Usuário cadastrado com sucesso')->flash();
                        Helpers::redirect('admin/users/users');
                    }else{
                        $this->message->error($user->error()->flash());
                        Helpers::redirect('admin/users/register');
                    }
                }
            }
        }

        echo $this->template->render('users/forms_users.html.twig',[
            'user' => $data
        ]);
        
    }

    public function edit(int $id)
    {
        $user = (new UserModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(isset($data)){
            if($this->validateData($data)){
                $user = (new UserModel())->findByID($id);

                $user->name      = $data['name'];
                $user->email     = $data['email'];
                $user->password  = (!empty($data['password']) ? $data['password'] : $user->password);
                $user->level     = $data['level'];
                $user->status    = $data['status'];
                $user->update_at =  date('Y-m-d H:i:s');

                if($user->save()){
                    $this->message->success('Usuario atualizado com sucesso')->flash();
                    Helpers::redirect('admin/users/users');
                }else{
                    $this->message->error($user->error())->flash();
                    Helpers::redirect('admin/users/register');
                }
            }
        }

        echo $this->template->render('users/forms_users.html.twig',[
            'user' => $user
        ]);
    }

    public function delete(int $id)
    {
        if(is_int($id)){
            $user = (new UserModel())->findByID($id);
            if(!$user){
                $this->message->alert('O usuário que você esta tentando deletar não existe!')->flash();
                Helpers::redirect('admin/users/users');
            }else{
                if($user->destroy()){
                    $this->message->success('Usuário deletado com sucesso!')->flash();
                    Helpers::redirect('admin/users/users');
                }else{
                    $this->message->error($user->error())->flash();
                    Helpers::redirect('admin/users/users');
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
    public function validateData(array $data): bool
    {
        if(empty($data['name'])){
            $this->message->alert('Informe o nome do usuario')->flash();
            return false;
        }
        if(empty($data['email'])){
            $this->message->alert('Informe o e-mail do usuário')->flash();
            return false;
        }
        if(!Helpers::validateEmail($data['email'])){
            $this->message->alert('Informe um e-mail válido')->flash();
            return false;
        }
        return true;
    }
}