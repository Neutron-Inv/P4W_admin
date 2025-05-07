<?php

namespace App\Mail\Core\User;

use App\Mail\Tag\UserTag;
use App\Models\Core\Auth\User;
use App\Notifications\Core\Helper\NotificationTemplateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;

    protected $verification_code;

    public function __construct(User $user, $verification_code)
    {
        $this->user = $user;
        $this->verification_code = $verification_code;
    }

    public function build()
    {
        $template = $this->template();

        $tag = new UserTag($this->user);

        return $this->view('notification.mail.user.template', [
            'template' => $template->parse(
                $tag->verifyEmail($this->verification_code)
            )
        ])->subject($template->parseSubject(
            method_exists($tag, 'verifyEmailSubject') ? $tag->passwordResetSubject() : ['{name}' => $this->user->full_name]
        ));
    }

    public function template()
    {
        return NotificationTemplateHelper::new()
            ->on('verify_email')
            ->mail();
    }
}
