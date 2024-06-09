<?php

namespace system\Controller\Admin;

use FontLib\EOT\Header;
use system\Core\Helpers;
use system\Model\UserModel;

class AdminUser extends AdminController
{


    /**
     * Lista usuários
     * @return void
     */
    public function lists(): void
    {
        $user = new UserModel();

        echo $this->template->render('users/users.html.twig', [
            'users' => $user->find()->order('level DESC, status ASC')->result(true),
            'total' => [
                'users' => $user->find('level != 3')->count(),
                'userActive' => $user->find('status = 1 AND level != 3')->count(),
                'userInactive' => $user->find('status = 0 AND level != 3')->count(),
                'admin' => $user->find('level = 3')->count(),
                'adminActive' => $user->find('status = 1 AND level = 3')->count(),
                'adminInactive' => $user->find('status = 0 AND level = 3')->count()
            ]
        ]);
    }

    /**
     * Cadastra usuário
     * @return void
     */
    public function register(): void
    {
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            //checa os data 
            if ($this->validateData($data)) {

                if (empty($data['password'])) {
                    $this->message->alert('Informe uma password para o usuário')->flash();
                } else {
                    $user = new UserModel();

                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    $user->password = $data['password'];
                    $user->level = $data['level'];
                    $user->status = $data['status'];

                    if ($user->save()) {
                        $this->message->success('Usuário cadastrado com sucesso')->flash();
                         Helpers::redirect('admin/users/users');
                    } else {
                        $user->message()->flash();
                    }
                }
            }
        }

        echo $this->template->render('users/forms_users.html.twig', [
            'user' => $data
        ]);
    }

    /**
     * Edita os data do usuário por ID
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        $user = (new UserModel())->findByID($id);

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if ($this->validateData($data)) {
                $user = (new UserModel())->findByID($id);

                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = (!empty($data['password']) ? $data['password'] : $user->password);
                $user->level = $data['level'];
                $user->status = $data['status'];
                $user->atualizado_em = date('Y-m-d H:i:s');

                if ($user->save()) {
                    $this->message->success('Usuário atualizado com sucesso')->flash();
                     Helpers::redirect('admin/users/users');
                } else {
                    $user->message()->flash();
                }
            }
        }

        echo $this->template->render('users/forms_users.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Checa os data do formulário
     * @param array $data
     * @return bool
     */
    public function validateData(array $data): bool
    {
        if (empty($data['name'])) {
            $this->message->alert('Informe o name do usuário')->flash();
            return false;
        }
        if (empty($data['email'])) {
            $this->message->alert('Informe o e-mail do usuário')->flash();
            return false;
        }
        if (!Helpers::validarEmail($data['email'])) {
            $this->message->alert('Informe um e-mail válido!')->flash();
            return false;
        }

        return true;
    }

    /**
     * Deletar um usuário por ID
     * @param int $id
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
                    $this->message->error($user->error())->flash();
                     Helpers::redirect('admin/users/users');
                }
            }
        }
    }

}
