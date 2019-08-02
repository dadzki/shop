<?php

namespace frontend\tests\unit\models;

use common\fixtures\UserFixture;
use frontend\forms\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    public function testWrongToken()
    {
        $this->tester->expectException('\yii\base\InvalidArgumentException', function() {
            new ResetPasswordForm('');
        });

        $this->tester->expectException('\yii\base\InvalidArgumentException', function() {
            new ResetPasswordForm('notexistingtoken_1391882543');
        });
    }

    public function testCorrectToken()
    {
        $user = $this->tester->grabFixture('user', 0);

        $form = new ResetPasswordForm(['password' => $user['password_reset_token']]);
        expect_that($form->validate());

        $form->password = 'new-pass';
        expect_that($form->validate());
    }
}
