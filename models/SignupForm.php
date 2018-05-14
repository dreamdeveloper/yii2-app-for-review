<?php

namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class SignupForm extends Model
{
    public $email;
    public $username;
    public $password;
    public $repeatpassword;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'username', 'password', 'repeatpassword'], 'required'],
            
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],

            [['password', 'repeatpassword'], 'string', 'min' => 6],
            ['repeatpassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'repeatpassword' => 'Retype password'    
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->payed_at = Yii::$app->formatter->asTimestamp(date('Y-m-d'));
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
