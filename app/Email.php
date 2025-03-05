<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Mail\Mailer;
class Email extends Model {

	
    protected $mailer;
    protected $data;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($toEmail)
    {
        $this->mailer->send('emails.test', ['name' => 'John Doe'], function ($message) use ($toEmail) {
            $message->to($toEmail)
                ->subject('Test Email from Mailtrap');
        });
    }
}
