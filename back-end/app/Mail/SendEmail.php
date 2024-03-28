<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
   use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $massage;
    public $name;
    public $title;
    public function __construct($massage ,$name,$title)
    {
      $this->title = $massage;
      $this->massage = $massage;
      $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        return $this->view('view.name');
        return $this->from('support@nafhasuha.com')
                    ->subject($this->title)
                    ->view('emails.SendEmail')
                     ->with('massage', $this->massage)
                     ->with('name',$this->name);
    }
}
