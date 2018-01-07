<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {

  public $email;

  public $password;

  public $rememberMe = TRUE;

  private $_user;


  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      ['email', 'email'],
      [['email', 'password'], 'required'],
      ['rememberMe', 'boolean'],
      ['password', 'validatePassword'],
    ];
  }

  /**
   * Validates the password.
   * This method serves as the inline validation for password.
   *
   * @param string $attribute the attribute currently being validated
   */
  public function validatePassword($attribute) {
    if (!$this->hasErrors()) {
      $user = $this->getUser();
      if (!$user || !$user->validatePassword($this->password)) {
        /** @var string $attribute */
        $this->addError($attribute, Yii::t('app', 'Incorrect email or password.'));
      }
    }
  }

  /**
   * Logs in a user using the provided username and password.
   *
   * @return bool whether the user is logged in successfully
   */
  public function login() {
    if ($this->validate()) {
      return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }

    return FALSE;
  }

  /**
   * Finds user by [[username]]
   *
   * @return User|null
   */
  protected function getUser() {
    if ($this->_user === NULL) {
      $this->_user = User::findByEmail($this->email);
    }

    return $this->_user;
  }
}
