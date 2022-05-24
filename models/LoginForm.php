<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
          'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
          'password' => [self::RULE_REQUIRED]
        ];
    }

    public function login()
    {
        // check if email already exist in db
        $user = User::findOne(['email' => $this->email]);
        if (!$user) {
            $this->addError('email', 'User does not exist with this email');
            return false;
        }

        // check if password is match
        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }


        return Application::$app->login($user);

    }

    // override label method in parent class and set the form label
    public function labels(): array
    {
        return [
            'email' => 'Your Email',
            'password' => 'Password',
        ];
    }

}