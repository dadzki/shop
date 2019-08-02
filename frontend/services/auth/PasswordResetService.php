<?php


namespace frontend\services\auth;


use common\entities\User;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use frontend\forms\SignupForm;
use Yii;

class PasswordResetService
{
    /**
     * @param PasswordResetRequestForm $form
     */
    public function sendRequest(PasswordResetRequestForm $form): void
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $form->email,
        ]);

        if (!$user) {
            throw new \DomainException('Пользователь не найден');
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                throw new \RuntimeException('Ошибка сохранения пользователя');
            }
        }

        $sentResult = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
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
        $user = User::findByPasswordResetToken($token);

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

        if (!User::findByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }
}