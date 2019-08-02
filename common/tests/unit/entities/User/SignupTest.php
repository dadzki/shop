<?php

namespace common\tests\unit\entities\User;


use common\entities\User;

class LoginFormTest extends \Codeception\Test\Unit
{
    public function testSuccess()
    {
        $user = User::signUp(
            $userName = 'username',
            $email = 'email',
            $password = 'password'
        );

        $this->assertEquals($userName, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertTrue($user->isInActive());

        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEmpty($user->created_at);
        $this->assertNotEmpty($user->auth_key);
    }
}
