<?php


namespace common\bootstrap;

use frontend\services\auth\PasswordResetService;
use frontend\services\auth\SignService;
use frontend\services\auth\VerifyEmailService;
use frontend\services\ContactService;
use Yii;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(PasswordResetService::class);
        $container->setSingleton(SignService::class);
        $container->setSingleton(VerifyEmailService::class);

        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail'],
        ]);


    }
}