<?php

namespace system\Controller;

use system\Core\Controller;
use system\Core\Helpers;
use system\Core\Session;
use system\Model\UserModel;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct('templates/users/views');
    }

    /**
     * Busca usuário pela sessão
     * @return UserModel|null
     */
    public static function user(): ?UserModel
    {
        $sessao = new Session();
        if(!$sessao->check('userId')){
            return null;
        }
        
        return (new UserModel())->findByID($sessao->userId);
    }

    public function register():void
    {
    
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($data)) {
            if (empty($data['name'])) {
                $this->message->alert('Informe seu nome')->flash();
            }elseif(empty($data['email'])){
              $this->message->alert('Informe seu e-mail')->flash();  
            }
            
            else {
                $user = new UserModel();

                $user->name = $data['name'];
                $user->email = $data['email'];

                if ($user->save()) {
                    $this->message->success('Cadastrado realizado com sucesso')->flash();
                    // Helpers::redirect();
                } else {
                    $user->message()->flash();
                }
            }
        }

        echo $this->template->render('register.html.twig', [
            'title' => 'Cadastre-se'
        ]);
    }

}
