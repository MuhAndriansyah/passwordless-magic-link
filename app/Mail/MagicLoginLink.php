<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class MagicLoginLink extends Mailable
{
    use Queueable, SerializesModels;

    public $plainTextToken;
    public $expiresAt;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($plaintTextToken, $expiresAt)
    {
        $this->plainTextToken = $plaintTextToken;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->subject('RnD Login Verification')->markdown('emails.magic-login-link', [
            'url' => URL::temporarySignedRoute('verify-login', $this->expiresAt, [
                'token' => base64_encode($this->plainTextToken),
            ])
        ]);
    }
}
