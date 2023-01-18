<?php
require PATH_HELPERS . 'PHPMailer-5.2-stable/PHPMailerAutoload.php';

class Mailer
{
    public $mail, $body, $to, $to_name, $subject, $msg, $from, $replyto, $replyto_name, $lang, $error;

    const Host = 'mail.foody.tn';

    const Username = 'noreply@foody.tn';

    const Password = 'bm9yZXBseWZvb2R5';

    const Port = 465;

    public function __construct()
    {
        $this->mail = new PHPMailer;
    }

    public function setFrom($pForm)
    {
        $this->from = $pForm;
        return $this;
    }
    public function setFromName($pForm)
    {
        $this->from_name = $pForm;
        return $this;
    }
    public function getFrom()
    {
        return $this->from;
    }
    public function getFromName()
    {
        return $this->from_name;
    }

    public function getTo()
    {
        return $this->to;
    }
    public function getToName()
    {
        return $this->to_name;
    }
    public function setTo($pTo)
    {
        $this->to = $pTo;
        return $this;
    }
    public function setToName($pToName)
    {
        $this->to_name = $pToName;
        return $this;
    }

    public function getReplyTo()
    {
        return $this->replyto;
    }
    public function getReplyToName()
    {
        return $this->replyto_name;
    }
    public function setReplyTo($pReplyTo)
    {
        $this->replyto = $pReplyTo;
        return $this;
    }
    public function setReplyToName($pReplyToName)
    {
        $this->replyto_name = $pReplyToName;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }
    public function setSubject($pSubj)
    {
        $this->subject = $pSubj;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }
    public function setBody($pBody)
    {
        $this->body = $pBody;
        return $this;
    }

    public function sendMail()
    {
        
        $this->mail->isSMTP();
        $this->mail->Host = $this::Host;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this::Username;
        $this->mail->Password = $this::Password;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port = $this::Port;
        $this->mail->CharSet = "UTF-8";
        $this->mail->setFrom($this->getFrom(), $this->getFromName());
        $this->mail->addAddress($this->getTo(), $this->getToName()); 
        // $this->mail->addReplyTo($this->getReplyTo(), $this->getReplyToName());

        $this->mail->isHTML(true);
        $this->mail->Subject = $this->getSubject();
        $this->mail->Body    = $this->getBody();

        if(!$this->mail->send()) {
            $this->error = $this->mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    }
}
?>