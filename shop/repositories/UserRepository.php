<?php


namespace shop\repositories;


use shop\entities\User;
use Yii;

class UserRepository
{
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token): User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return User::findOne([
            'password_reset_token' => $token,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token): User
    {
        return User::findOne([
            'verification_token' => $token,
            'status' => User::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username, 'status' => User::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return User::findOne(['email' => $email, 'status' => User::STATUS_ACTIVE]);
    }

    public static function findByEmailInactive($email)
    {
        return User::findOne(['email' => $email, 'status' => User::STATUS_INACTIVE]);
    }


    public function findByUsernameOrEmail($value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()
            ->joinWith('networks n')
            ->andWhere(['n.network' => $network, 'n.identity' => $identity])
            ->one();
    }

    public function getById($id): User
    {
        return User::findOne(['id' => $id]);
    }

}
