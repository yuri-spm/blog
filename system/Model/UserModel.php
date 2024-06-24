<?php

namespace system\Model;

use system\Core\Helpers;
use system\Core\Session;
use system\Core\Model;

class UserModel extends Model
{    
    /**
     * __construct
     * BD user
     * @return void
     */
    public function __construct()
    {
        parent::__construct('user');
    }
      
    /**
     * findByEmail
     *
     * @param  mixed $email
     * @return UserModel
     */
    public function findByEmail(string $email): ?UserModel
    {
        $busca = $this->find("email = :e","e={$email}");
        return $busca->result();
    }
    
    
    /**
     * login
     *
     * @param  mixed $data
     * @param  mixed $level
     * @return void
     */
    public function login(array $data, int $level = 1)
    {
        $user = (new UserModel())->findByEmail($data['email']);
        
        if(!$user){
            $this->message->error("Os dados informados para o login estão incorretos!")->flash();
            return false;
        }
        
        if(!Helpers::verifyPassword($data['password'], $user->password)){
            $this->message->alert("Os dados informados para o login estão incorretos!")->flash();
            return false;
        }
        
        if($user->status != 1){
            $this->message->alert("Para fazer login, primeiro ative sua conta!")->flash();
            return false;
        }
        
        if($user->level < $level){
            $this->message->error("Você não tem permissão para acessar essa área!")->flash();
            return false;
        }
        
        //salva a data e hora do login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
        
        //cria uma sessão com o id
        (new Session())->create('userId', $user->id);
        
        $this->message->success("{$user->name}, seja bem vindo ao painel de controle")->flash();
        return true;
    }
    
        
    /**
     * save
     *
     * @return bool
     */
    public function save(): bool
    {
        $params = [
            'e' => $this->email,
            'id' => $this->id
        ];
    
        if($this->find("email = :e AND id != :id", $params)->result()){
            $this->message->alert("O e-mail ".$this->data->email." já está cadastrado");
            return false;
        }
        
        parent::save();
        
        return true;
    }
}
