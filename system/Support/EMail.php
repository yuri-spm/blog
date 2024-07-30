<?php

namespace system\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use system\Model\EmailModel;

final class Email
{
    private PHPMailer $mail;
    private array $attachments;

    public function __construct()
    {
        $email = (new EmailModel())->find()->result();

        $this->mail = new PHPMailer(true);
        

        $this->mail->isSMTP();
        $this->mail->Host = $email->smtp_server;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $email->admin_email;
        $this->mail->Password =  "";
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->setLanguage('pt_br');
        $this->mail->Port = $email->smtp_port;

        $this->mail->CharSet = 'uft-8';
        $this->mail->isHTML(true);

        $this->attachments = [];
    }


    public function create(
        string $subject,
        string $content,
        string $recipientEmail,
        string $recipientName,
        ?string $answerTo = null,
        ?string $answerName = null
    ):static{
        $this->mail->Subject = $subject;
        $this->mail->Body    = $content;
        $this->mail->isHTML($content);
        $this->mail->addAddress($recipientEmail, $recipientName);

        if($answerName !== null && $answerName !==null){
            $this->mail->addReplyTo($answerTo, $answerName);
        }


        return $this;        
    }

    public function send($recipientEmail, $recipientName) : bool {
        try{
            $this->mail->addAddress($recipientEmail, $recipientName);


            foreach($this->attachments as $attachment){
                $this->mail->addAttachment($attachment['directory'], $attachment['name']);
            }

            $this->mail->send();
            return true;
        }catch(Exception $ex){
            $ex = $this->mail->ErrorInfo;
            return false;
        }
    }

    public function attachment(string $directory, ?string $name = null): static
    {
        $name = $name ?? basename($directory);

        $this->attachments[] =[
            'directory' => $directory,
            'name'      => $name
        ];

        return $this;
    }
}