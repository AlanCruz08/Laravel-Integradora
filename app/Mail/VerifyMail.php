<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function build()
    {
        return $this->view('email', ['number' => $this->number]);
    }
}
