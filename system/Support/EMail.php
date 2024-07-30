<?php

namespace system\Support;

use system\Model\EmailModel;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

final class Email
{
    private PHPMailer $mail;
    private array $attachments;

    public function __construct()
    {
        $email = (new EmailModel())->find()->result();

        $this->mail = new PHPMailer(true);
        
        // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER; 
        $this->mail->isSMTP();
        $this->mail->Host = $email->smtp_server;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $email->admin_email;
        $this->mail->Password =  "bghs jcma wnig xgal";
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->setLanguage('pt_br');
        $this->mail->Port = $email->smtp_port;

        $this->mail->CharSet = 'UTF-8';
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
        $content.= $this->footer();
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

    private function footer() : string
    {
        return '<div style="font-family: Arial, sans-serif; font-size: 12px; color: #333; text-align: center; border-top: 1px solid #e0e0e0; padding-top: 20px; margin-top: 20px;">
        <div style="margin-top: 10px;">
            <a href="https://www.linkedin.com/in/seu-perfil" target="_blank" style="margin: 0 10px; text-decoration: none;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" alt="LinkedIn" style="height: 24px; width: 24px;">
            </a>
            <a href="https://www.instagram.com/seu-perfil" target="_blank" style="margin: 0 10px; text-decoration: none;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram" style="height: 24px; width: 24px;">
            </a>
            <a href="https://www.youtube.com/c/seu-canal" target="_blank" style="margin: 0 10px; text-decoration: none;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/4/42/YouTube_icon_%282013-2017%29.png" alt="YouTube" style="height: 24px; width: 24px;">
            </a>
        </div>
        <p style="font-size: 10px; color: #888; margin-top: 10px;">
            Este e-mail e qualquer arquivo anexo são confidenciais e destinados exclusivamente ao(s) destinatário(s) indicado(s). Se você recebeu este e-mail por engano, por favor, informe o remetente e apague-o de seu sistema.
        </p>
    </div>';
    
    }
}