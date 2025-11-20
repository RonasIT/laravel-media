<?php

namespace RonasIT\Media\Tests\Models\custom;

use Illuminate\Contracts\Auth\Authenticatable;

class CustomUser implements Authenticatable
{
    public function __construct(
        public int $id,
        public string $username,
        public string $password,
    ) {
    }

    public function getAuthIdentifierName(): string
    {
        return $this->username;
    }

    public function getAuthIdentifier(): int
    {
        return $this->id;
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }
}