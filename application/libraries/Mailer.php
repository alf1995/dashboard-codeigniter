<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Mailer { 

    protected $to; 
    protected $subject; 
    protected $userName; 
    protected $password; 
    protected $emailHost; 
    protected $mailFrom; 
    protected $mailDirectory; 


    public function __construct() { 
        $this->userName = getenv('MAIL_NAME');
        $this->password = getenv('MAIL_PASSWORD');
        $this->emailHost = getenv('MAIL_HOST');
        $this->mailFrom = [getenv('MAIL_FROM_EMAIL') => getenv('MAIL_FROM_NAME')];
        $this->mailDirectory = VIEWPATH . '/emails';
    }

    protected function init()
    {
        $transport = (new Swift_SmtpTransport($this->emailHost, getenv('MAIL_PORT'), getenv('MAIL_ENCRYPTION')))
                        ->setUsername($this->userName)
                        ->setPassword($this->password);
        $mailer = new Swift_Mailer($transport);
        return $mailer;
    }

    protected function initializeTemplate( $template, $__data ) {
        ob_start();
        extract($__data);
        include $this->mailDirectory .'/'.$template;
        return ltrim(ob_get_clean());
    }

    public function to($email) 
    {
        $this->to = $email;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function send( $view, array $data = [] )
    {
        $mailer = $this->init();
        $template = $this->initializeTemplate($view, $data);
        $message = (new Swift_Message($this->subject))
                    ->setFrom($this->mailFrom)
                    ->setTo([$this->to])
                    ->setBody($template, 'text/html');
        $result = $mailer->send($message);
    }
}