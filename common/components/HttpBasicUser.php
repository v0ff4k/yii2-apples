<?php
namespace common\components;

use yii\web\IdentityInterface;

/**
 * Class HttpBasicUser
 * @package common\components
 */
class HttpBasicUser implements IdentityInterface
{
    public $id = 1;
    public $username = 'admin';

    /**
     * @param int|string $id
     * @return \common\components\HttpBasicUser|\yii\web\IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return new self();
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return \yii\web\IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @param $username
     * @return \common\components\HttpBasicUser
     */
    public static function findByUsername($username)
    {
        return new self();
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAuthKey()
    {
        return '';
    }

    /**
     * @param string $authKey
     * @return bool|null
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }
}
