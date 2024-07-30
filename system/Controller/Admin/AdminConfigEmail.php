<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Support\Email;
use system\Model\EmailModel;
use PHPMailer\PHPMailer\Exception;

class AdminConfigEmail extends AdminController
{  

    public function configEmail()
    {
        $email =  (new EmailModel())->findByID(1);
        echo $this->template->render('config/config.html.twig', [
            "email" => $email
        ]);
    }
    public function add()
    {
        $email = new EmailModel();
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $email->admin_email   = $data['admin_email'];
        $email->sender_email  = $data['sender_email'];
        $email->smtp_server   = $data['smtp_server'];
        $email->smtp_login    = $data['smtp_login'];
        $email->smtp_port     = $data['smtp_port'];
        $email->smtp_password = Helpers::generatePassword($data['smtp_password']);
        $email->status        = $data['status'];
        $email->comments      = $data['comments'];

        if($email->save()){
            $this->message->success('Configurado com sucesso')->flash();
        }else{
            $this->message->alert('Não foi possível atualizar os dados por favor entre em contato com o fornecedor')->flash();
        }
        
        echo $this->template->render('config/config.html.twig', [
            'email' => $email
        ]);
    }

    public function edit($id)
    {
        $email =  (new EmailModel())->findByID($id);
        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $email->admin_email   = $data['admin_email'];
        $email->sender_email  = $data['sender_email'];
        $email->smtp_server   = $data['smtp_server'];
        $email->smtp_login    = $data['smtp_login'];
        $email->smtp_port     = $data['smtp_port'];
        $email->smtp_password = Helpers::generatePassword($data['smtp_password']);
        $email->status        = $data['status'];
        $email->comments      = $data['comments'];

        if($email->save()){
            $this->message->success('Atualizado com sucesso')->flash();
        }else{
            $this->message->alert('Não foi possível atualizar os dados por favor entre em contato com o fornecedor')->flash();
        }
        
        echo $this->template->render('config/config.html.twig', [
            'email' => $email
        ]);
    }

    public function sendTeste()
    {
        try{
            $email = new Email();
            $email->create('Teste de e-mail', 'Teste de e-mail', 'yspm.developer@gmail.com', 'Administrador');
            $email->send('yspm.developer@gmail.com', 'Helpx');
            echo "mensagem enviada com sucesso";
          }catch(Exception $e){
              echo $e->getMessage();
          }

    }
}