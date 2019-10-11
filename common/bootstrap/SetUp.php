<?php


namespace common\bootstrap;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use shop\cart\Cart;
use shop\cart\cost\calculator\SimpleCost;
use shop\cart\storage\SessionStorage;
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

        $container->setSingleton(Client::class, function () {
            return ClientBuilder::create()->setHosts(['elasticsearch'])->build();
        });

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

        $container->setSingleton(Cart::class, function () {
            return new Cart(
                new SessionStorage('cart'),
                new SimpleCost()
            );
        });
    }
}
