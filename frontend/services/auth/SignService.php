<?php


namespace frontend\services\auth;


use common\entities\User;
use frontend\forms\SignupForm;
use Yii;

class SignService
{
    public function signUp(SignupForm $form): User
    {
        $user = User::signUp($form->username, $form->email, $form->password);

        if (!$user->save) {
            throw new \RuntimeException('Ошибка при сохранении пользователя');
        }

        $this->sendEmail($user);

        return $user;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}