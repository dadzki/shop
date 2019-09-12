<?php


namespace shop\services;


use shop\entities\User;
use shop\forms\ContactForm;
use shop\forms\auth\SignupForm;
use http\Exception\RuntimeException;
use Yii;
use yii\mail\MailerInterface;

class ContactService
{
    protected $adminEmail;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * ContactService constructor.
     * @param $adminEmail
     * @param MailerInterface $mailer
     */
    public function __construct($adminEmail, MailerInterface $mailer)
    {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }


    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param ContactForm $form
     * @return void whether the email was sent
     */
    public function send(ContactForm $form): void
    {
        $sentResult =  $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setReplyTo([$form->email => $form->name])
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if (!$sentResult) {
            throw new \RuntimeException('Ошибка отправки сообщения');
        }
    }
}
