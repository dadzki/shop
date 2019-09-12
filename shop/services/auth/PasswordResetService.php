<?php


namespace shop\services\auth;

use shop\entities\User;
use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;
use shop\repositories\UserRepository;
use Yii;
use yii\mail\MailerInterface;

class PasswordResetService
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    protected $users;

    /**
     * PasswordResetService constructor.
     * @param MailerInterface $mailer
     * @param UserRepository $users
     */
    public function __construct(MailerInterface $mailer, UserRepository $users)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    /**
     * @param PasswordResetRequestForm $form
     */
    public function sendRequest(PasswordResetRequestForm $form): void
    {
        /* @var $user User */
        $user = $this->users->findByEmail($form->email);

        if (!$user) {
            throw new \DomainException('Пользователь не найден');
        }

        if (!$this->users->isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                throw new \RuntimeException('Ошибка сохранения пользователя');
            }
        }

        $sentResult = $this->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if (!$sentResult) {
            throw new \RuntimeException('Ошибка отправки запроса пользователю по смене пароля');
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @param ResetPasswordForm $form
     * @return void if password was reset.
     */
    public function resetPassword(string $token, ResetPasswordForm $form): void
    {
        $user = $this->users->findByPasswordResetToken($token);

        if (!$user) {
            throw new \DomainException('Пользователь не найден');
        }

        $user->setPassword($form->password);
        $user->removePasswordResetToken();

        if (!$user->save(false)) {
            throw new \RuntimeException('Ошибка сохранения пользователя');
        }
    }


    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }

        if (!$this->users->findByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }
}
