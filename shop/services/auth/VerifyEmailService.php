<?php


namespace shop\services\auth;

use shop\entities\User;
use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResendVerificationEmailForm;
use shop\forms\auth\ResetPasswordForm;
use shop\forms\auth\VerifyEmailForm;
use shop\repositories\UserRepository;
use Yii;
use yii\mail\MailerInterface;

class VerifyEmailService
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    protected $users;

    /**
     * PasswordResetService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer, UserRepository $users)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    /**
     * Sends confirmation email to user
     *
     * @param ResendVerificationEmailForm $form
     * @return void whether the email was sent
     */
    public function sendRequest(ResendVerificationEmailForm $form)
    {
        /* @var $user User */
        $user = $this->users->findByEmailInactive($form->email);

        if (!$user) {
            throw new \DomainException('Пользователь не найден');
        }

        $sentResult = $this->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Account registration at ')
            ->send();

        if (!$sentResult) {
            throw new \RuntimeException('Ошибка отправки запроса пользователю на уточнение email');
        }

    }

    /**
     * Verify email
     *
     * @param string $token
     * @param VerifyEmailForm $form
     * @return void the saved model or null if saving fails
     */
    public function verifyEmail(VerifyEmailForm $form)
    {
        $user = $this->users->findByVerificationToken($form->token);

        if (!$user) {
            throw new \DomainException('Пользователь не найден');
        }

        $user->status = User::STATUS_ACTIVE;

        if (!$user->save(false)) {
            throw new \RuntimeException('Ошибка сохранения пользователя');
        }
    }

    /**
     * @param $token
     */
    public function validateVerifyToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }

        if (!$this->users->findByVerificationToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }
}
