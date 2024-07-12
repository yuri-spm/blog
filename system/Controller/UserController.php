<?php

namespace system\Controller;

use system\Core\Controller;
use system\Core\Session;
use system\Model\UserModel;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct('templates/site/views');
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

}
