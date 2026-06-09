<?php

declare(strict_types=1);

namespace common\components;

use yii\web\IdentityInterface;

class HttpBasicUser implements IdentityInterface
{
    public int $id = 1;

    public string $username = 'admin';

    public static function findIdentity($id): self
    {
        return new self();
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return null;
    }

    public static function findByUsername(string $username): self
    {
        return new self();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return '';
    }

    public function validateAuthKey($authKey): bool
    {
        return true;
    }
}
