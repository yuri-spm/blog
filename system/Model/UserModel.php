<?php

namespace system\Model;

use system\Core\Helpers;
use system\Core\Session;
use system\Core\Model;

class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct('user');
    }
    
    /**
     * Busca usuário por e-mail
     * @param string $email
     * @return UserModel|null
     */
    public function findByEmail(string $email): ?UserModel
    {
        $find = $this->find("email = :e","e={$email}");
        return $find->result();
    }
    
    /**
     * Valida o login do usuário
     * @param array $data
     * @param int $level
     * @return boolean
     */
    public function login(array $data, int $level = 1)
    {
        $user = (new UserModel())->findByEmail($data['email']);
        
        if(!$user){
            $this->message->error("Os data infodos para o login estão incorretos!")->flash();
            return false;
        }
        
        if(!Helpers::verifyPassword($data['password'], $user->password)){
            $this->message->alert("Os data infodos para o login estão incorretos!")->flash();
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
    
    
    public function save(): bool
    {
        if($this->find("email = :e AND id != :id","e={$this->email}&id={$this->id}")->result()){
                $this->message->alert("O e-mail ".$this->data->email." já está cadastrado");
                return false;
            }
        
            parent::save();
            
        return true;
    }
}
