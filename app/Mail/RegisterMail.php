<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $subject;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $message, $token)
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from('webImages@app.com')->subject($this->subject)->view('emails.RegisterMail');

        $email->with([
            'data' => ['message' => $this->message, 'link' => url('/') . "login?token=$this->token"],
        ])->priority(1);
    }
}
