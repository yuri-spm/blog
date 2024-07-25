<?php

namespace system\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



final class Email
{
    private PHPMailer $mail;
    private array $attachments;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "yspm.developer@gmail.com";
        $this->mail->Password = "puoeqevbizqeqwhd";
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->setLanguage('pt_br');
        $this->mail->Port = 465;

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