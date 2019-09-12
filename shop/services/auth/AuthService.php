<?php


namespace shop\services\auth;


use shop\forms\LoginForm;
use shop\entities\User;
use shop\repositories\UserRepository;
use yii\mail\MailerInterface;

class AuthService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    protected $users;

    /**
     * ContactService constructor.
     * @param MailerInterface $mailer
     * @param UserRepository $users
     */
    public function __construct(MailerInterface $mailer, UserRepository $users)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);
        if (!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Undefined user or password.');
        }
        return $user;
    }
}
