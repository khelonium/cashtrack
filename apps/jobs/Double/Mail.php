<?php
namespace Jobs\Double;

class Mail extends \Library\Mail
{
    public $called  = true;

    public function send($recipient = null)
    {
        $this->called = true;
    }


    public function getSubject()
    {
        return $this->subject;
    }

}