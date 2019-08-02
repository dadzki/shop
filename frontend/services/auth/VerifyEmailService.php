<?php


namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResendVerificationEmailForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\VerifyEmailForm;
use Yii;
use yii\mail\MailerInterface;

class VerifyEmailService
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * PasswordResetService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Sends confirmation email to user
     *
     * @param ResendVerificationEmailForm $form
     * @return void whether the email was sent
     */
    public function sendRequest(ResendVerificationEmailForm $form)
    {
        $user = User::findOne([
            'email' => $form->email,
            'status' => User::STATUS_INACTIVE
        ]);

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
        $user = User::findByVerificationToken($form->token);

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

        if (!User::findByVerificationToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }
}