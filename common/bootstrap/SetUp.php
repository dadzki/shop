<?php


namespace common\bootstrap;

use shop\services\auth\AuthService;
use shop\services\auth\NetworkService;
use shop\services\auth\PasswordResetService;
use shop\services\auth\SignService;
use shop\services\auth\VerifyEmailService;
use shop\services\ContactService;
use Yii;
use yii\base\BootstrapInterface;
use yii\caching\Cache;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(PasswordResetService::class);
        $container->setSingleton(SignService::class);
        $container->setSingleton(VerifyEmailService::class);
        $container->setSingleton(AuthService::class);
        $container->setSingleton(NetworkService::class);

        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail'],
        ]);


    }
}
