<?php

namespace MyProject\Models\Users;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;


class User extends ActiveRecordEntity
{
    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;

    /** @var string */
    protected $createdAt;

    /**
     * @return string
     */

    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string
     */

     public function getEmail(): string
     {
         return $this->email;
     }

     /**
      * @return bool
      */
 
     public function isAdmin(): bool
     {
         return $this->role === 'admin';
     }

    /**
     * @return string
     */

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @return string
     */

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
    }
    public static function signUp(array $userData): User
    {
        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан nickname');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])) {
            throw new InvalidArgumentException('nickname может состоять только из символов латинского алфавита и цифр');
        }
        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email не корректен');
        }
        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }
        if (static::findOneByColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким nickmane же существует');
        }
        if (static::findOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким email же существует');
        }

        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->password_hash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->isConfirmed = false;
        $user->role = 'user';
        $user->authToken = sha1(random_bytes(100) . sha1(random_bytes(100)));
        $user->save();

        return $user;
    }

    public static function login(array $loginData): User
    {

        if (empty($loginData['email'])) {
            throw new InvalidArgumentException("Не передан email");
        }

        if (empty($loginData['password'])) {
            throw new InvalidArgumentException("Не передан password");
        }

        $user = User::findOneByColumn('email', $loginData['email']);
        if ($user === null) {
            throw new InvalidArgumentException("Нет пользователя с таким email");
        }

        if (!password_verify($loginData['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Неправильный пароль');
        }

        if (!$user->isConfirmed) {
            throw new InvalidArgumentException("Пользователь не подтверждён");
        }

        $user->refreshAuthToken();
        $user->save();

        return $user;
    }

    protected static function getTableName(): string
    {
        return 'users';
    }

    private function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }

    public static function logout()
    {
        setcookie('token', null, -1, '/');
    }

}
