<?php

namespace system\Model;

use system\Core\Model;

class UserModel extends Model
{
    public function __construct()
    {
      parent:: __construct('user');
    }     

    public function findByEmail(string $email): ? UserModel
    {
        $find = $this->find("email = :e", "e={$email}");
        return $find->result();
    }
     
    public function login( array $data, int $level = 1)
    {
        $user = (new UserModel())->findByEmail($data['email']);

        if(!$user){
            $this->message->alert("Usuario ou senha incorreta")->flash();
            return false;
        }

        if($data['password'] != $user->password){
            $this->message->alert("Usuario ou senha incorreta")->flash();
            return false;
        }

        if($user->status != 1){
            $this->message->alert("Usuario inativo")->flash();
            return false;
        }

        if($user->level < $level){
            $this->message->alert("Usuario sem permissão")->flash();
            return false;
        }

        $this->message->success("{$user->name}, seja bem vindo ao painel de controle")->flash();
        return true;
    } 
}