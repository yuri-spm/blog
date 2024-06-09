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
    public function lists(): void
    {
        $users = new UserModel();

        echo $this->template->render('users/users.html.twig', [
            'users' => $users->find()->order('level DESC, status ASC')->result(true),
            'total' => [
                'users' => $users->find('level != 3')->count(),
                'usersActive' => $users->find('status = 1 AND level != 3')->count(),
                'userInactive' => $users->find('status = 0 AND level != 3')->count(),
                'admin' => $users->find('level = 3')->count(),
                'adminActive' => $users->find('status = 1 AND level = 3')->count(),
                'adminInactive' => $users->find('status = 0 AND level = 3')->count()
            ]
        ]);
    }

    
    /**
     * register
     *
     * @return void
     */
    public function register(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            //checa os data 
            if ($this->validateData($data)) {

                if (empty($data['senha'])) {
                    $this->message->alert('Informe uma senha para o usuário')->flash();
                } else {
                    $user = new UserModel();

                    $user->nome = $data['nome'];
                    $user->email = $data['email'];
                    $user->senha = $data['senha'];
                    $user->level = $data['level'];
                    $user->status = $data['status'];

                    if ($user->save()) {
                         $this->message->success('Usuário cadastrado com sucesso')->flash();
                          Helpers::redirect('admin/users/users');
                    } else {
                        $user->mensagem()->flash();
                    }
                }
            }
        }

        echo $this->template->render('usuarios/formulario.html', [
            'usuario' => $data
        ]);
    }
    
    /**
     * editar
     *
     * @param  mixed $id
     * @return void
     */
    public function editar(int $id): void
    {
        $user = (new UserModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $user = (new UserModel())->findByID($id);

                $user->nome = $data['nome'];
                $user->email = $data['email'];
                $user->senha = (!empty($data['senha']) ? $data['senha'] : $user->senha);
                $user->level = $data['level'];
                $user->status = $data['status'];
                $user->atualizado_em = date('Y-m-d H:i:s');

                if ($user->save()) {
                     $this->message->success('Usuário atualizado com sucesso')->flash();
                      Helpers::redirect('admin/users/users');
                } else {
                    $user->mensagem()->flash();
                }
            }
        }

        echo $this->template->render('usuarios/formulario.html', [
            'usuario' => $user
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
        if (empty($data['name'])) {
            $this->message->alert('Informe o nome do usuário')->flash();
            return false;
        }
        if (empty($data['email'])) {
            $this->message->alert('Informe o e-mail do usuário')->flash();
            return false;
        }
        if (!Helpers::validateEmail($data['email'])) {
            $this->message->alert('Informe um e-mail válido!')->flash();
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
    public function delete(int $id): void
    {
        if (is_int($id)) {
            $user = (new UserModel())->findByID($id);
            if (!$user) {
                $this->message->alert('O usuário que você está tentando deletar não existe!')->flash();
                  Helpers::redirect('admin/users/users');
            } else {
                if ($user->destroy()) {
                     $this->message->success('Usuário deletado com sucesso!')->flash();
                      Helpers::redirect('admin/users/users');
                } else {
                    $this->message->error($user->erro())->flash();
                      Helpers::redirect('admin/users/users');
                }
            }
        }
    }

}
