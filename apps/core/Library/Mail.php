<?php
namespace Library;

/**
 * Class Mail wraps email call.
 * Will be properly done if ever needed
 * @package Library
 */
class Mail
{

    /**
     * I will care when somebody will actually use this code
     */
    protected $to = 'cosmin.dordea@yahoo.com';
    protected $subject = null;
    protected $content = "Sent from finance";


    public function send($recipient = null)
    {
        $recipient and $this->to($recipient);

        mail($this->to, $this->subject, $this->content, $this->getHeaders());
    }

    public function to($recipient)
    {
        $this->to = $recipient;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    private function getHeaders()
    {
        return 'From: finance@refactoring.ro' . "\r\n" .
        'Reply-To: no-reply@refactoring.ro' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    }
}
