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
    public function __construct($massage ,$name)
    {
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
        return $this->from('garageqatar@sherifshalaby.tech')
                    ->subject('Garage')
                    ->view('emails.SendEmail')
                     ->with('massage', $this->massage)
                     ->with('name',$this->name);
    }
}
