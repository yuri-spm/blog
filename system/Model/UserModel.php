<?php

namespace system\Model;

use system\Core\Session;
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

        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        (new Session())->create('userId', $user->id);
        
        $this->message->success("{$user->name}, seja bem vindo ao painel de controle")->flash();
        return true;
    } 

    public function save()
    {
        //CADASTRAR
        if(empty($this->id)){
            if($this->findByEmail($this->email)){
                $this->message->alert('E-mail '.$this->data->email.' ja cadastrado');
                return false;
            }
            $id = $this->create($this->store());
            if($this->error){
                $this->mensagem->error('error de sistema ao tentar cadastrar.');
                return false;
            }
        }
        
        //ATUALIZAR
        if(!empty($this->id)){
            $id = $this->id;
            if($this->findByEmail($this->email)){
                $this->message->alert('E-mail '.$this->data->email.' ja cadastrado');
                return false;
            }
            $this->update($this->store(), "id = {$id}");
            if($this->error){
                $this->mensagem->error('error oo tentar atualizar '.$this->data->name.'.');
                return false;
            }
        }
        
        $this->data = $this->findByID($id)->data();
        return true;
    }
}