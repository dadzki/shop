<?php


namespace shop\services\auth;

use shop\access\Rbac;
use shop\entities\User;
use shop\forms\auth\SignupForm;
use Yii;
use shop\services\RoleManager;
use shop\services\TransactionManager;
use yii\mail\MailerInterface;

class SignService
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    private $roles;
    private $transaction;

    /**
     * ContactService constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(
        MailerInterface $mailer,
        RoleManager $roles,
        TransactionManager $transaction
    )
    {
        $this->mailer = $mailer;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    /**
     * @param SignupForm $form
     * @return User
     */
    public function signUp(SignupForm $form): User
    {
        $user = User::signUp($form->username, $form->email, $form->password);

        $this->transaction->wrap(function () use ($user) {
            if (!$user->save()) {
                throw new \RuntimeException('Ошибка при сохранении пользователя');
            }
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });



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
        return $this->mailer
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
