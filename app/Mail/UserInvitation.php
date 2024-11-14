<?php

namespace App\Mail;

use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;
    
    public $inviter;
    public $invitation_code;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $inviter, string $invitation_code)
    {
        $this->inviter = $inviter;
        $this->invitation_code = $invitation_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invitation from ' . $this->inviter->name)
                    ->replyTo($this->inviter->email)
                    ->markdown('emails.userInvitation');
    }
}
