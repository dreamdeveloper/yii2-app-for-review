<?php
namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_ACTIVE_OFFER = 1;
    const ROLE_USER = 10;
    const ROLE_ADMINISTRATOR = 15;

    public $oldPassword;
    public $newPassword;
    public $repeatPassword;
    public $file3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getUserName()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'repeatPassword'], 'required', 'on' => 'changePassword'],
            ['oldPassword', 'correctPassword', 'on' => 'changePassword'],
            ['repeatPassword', 'equalsPassword', 'on' => 'changePassword'],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['name', 'unique'],
            ['username', 'string'],
            ['file3', 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 5, 'tooBig' => 'The file is too big. Image size shouldn\'t exceed 5MB'],
            ['logo_image', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'logo_image' => 'Logo Image',
            'file3' => 'Upload Logo Image',
        ];
    }
            
    public function correctPassword($attribute, $params)
    {
        if (!$this->validatePassword($this->oldPassword)) {
            $this->addError($attribute, 'This password is incorrect');
        }
    }

    public function equalsPassword($attribute, $params)
    {
        if ($this->repeatPassword !== $this->newPassword) {
            $this->addError($attribute, 'Passwords don\'t match');
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function getUser()
    {
        return static::findIdentity(Yii::$app->user->getId());
    }
    
    public static function getExpiresDate(){
        $user = static::getUser();
        $calc_exp_date = date("Y-m-d", mktime(0, 0, 0, date("m", $user->payed_at)+1, date("d", $user->payed_at), date("Y", $user->payed_at))); 
        
        return $calc_exp_date;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * @return role
     */
    public function isAdmin()
    {        
        $user = static::getUser();
        if( !$user ){
            return false;
        } else {
            return $user->role == self::ROLE_ADMINISTRATOR ? true : false;   
        }
    }
    
    /**
     * @return string
     */
    public static function getPictureFolder()
    {
        return Yii::getAlias('@app') . '/web/images/uploads/users/';
    }
    
    public static function getLogoImage($offerId = false)
    {
        $user = User::find()->innerJoin('UserOffer', 'UserOffer.userId = user.id')
            ->andWhere(['UserOffer.offerId' => $offerId])
            ->andWhere(['UserOffer.active' => 1])->one();
            
        if( !$user ){
            return 0;
        } else {
            return $user->logo_image;
        }
    }
    
      /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOffer()
    {
        return $this->hasMany(UserOffer::className(), ['userId' => 'userId']);
    }
    
}
