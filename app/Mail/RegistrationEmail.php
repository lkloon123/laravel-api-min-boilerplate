<?php

namespace App\Mail;

use App\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $userProfile;
    public $userActivationLink;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $userProfile
     * @param $apiToken
     */
    public function __construct(User $user, $userProfile, $apiToken)
    {
        $this->user = $user;
        $this->userProfile = $userProfile;
        $this->userActivationLink = config('app.url') . '/api/auth/email/verify?e=' . $user->email . '&t=' . $apiToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('User Registration')->view('emails.auth.registration');
    }
}
