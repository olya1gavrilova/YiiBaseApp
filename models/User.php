<?php

namespace app\models;

use Yii;

use yii\base\Security;

use yii\web\IdentityInterface;
 use yii\db\ActiveRecord;
  
 use yii\web\Link;
 use yii\web\Linkable;
 use yii\helpers\Url;


class User extends ActiveRecord implements IdentityInterface, Linkable
{

    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirm;


    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    /**
     * @intval(var)heritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
   
    public $new_password;
    public $captcha;


    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => [ 'first_name', 'username','email', 'password', 'captcha'],
            self::SCENARIO_UPDATE => [ 'last_name', 'first_name', 'username','email',],
        ];
    }
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        
        return [
            [['first_name', 'username', 'email', 'password','captcha'], 'required','on' => self::SCENARIO_CREATE],
            [['first_name', 'username', 'email', ], 'required','on' => self::SCENARIO_UPDATE],
            [['last_name', 'first_name'], 'string', 'max' => 45],
            [['username'], 'string', 'max' => 15],
            ['email','email'],
            ['captcha','captcha'],
            ['username', 'unique', 'message'=>'Этот никнейм уже занят'],
            [['password', 'auth_key', 'access_token'], 'string', 'max' => 32],

            [['currentPassword', 'newPassword', 'newPasswordConfirm', ], 'required','on' => 'change_password'],
            [['currentPassword'], 'validateCurrentPassword'],
            [['newPassword', 'newPasswordConfirm'], 'string', 'min'=>3],
            [['newPassword', 'newPasswordConfirm'], 'filter', 'filter'=>'trim'],
            [['newPasswordConfirm'], 'compare','compareAttribute'=>'newPassword', 'message'=>'Пароли не совпадают'],
        ];
    }

    public function validateCurrentPassword()
    {
        if(md5($this->currentPassword)!==User::findIdentity(['username'=>Yii::$app->user->identity->username])->password){
           $this->addError('currentPassword', 'Неверно введен текущий пароль');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }
    

     public static function findIdentity($id)
    {
       return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token'=>$token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username'=> $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
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
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function isAuthor(){
           return $this->id==Yii::$app->user->id ? true: false;
    }

    public function tokenGenerator(){
        $security = new Security();
        return $security->generateRandomString(32);
    }


   public function getLinks()
    {
         return [
             Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
        ];
     }
   
  // отбрасываем некоторые поля. Сделано для рест апи, не ясно на что еще влияет
   public function fields()
   {
       $fields = parent::fields();
 
      // удаляем не безопасные поля
      unset($fields['password'], $fields['auth_key'], $fields['access_token']);
 
      return $fields;
  }
  public function createuser()
   {
        $token=$this->tokenGenerator();
       $this->access_token= $token;
            $this->password=md5($this->password);
            //$model->validate();
            $this->save();

            $id=User::findIdentityByAccessToken($token)->id;
            $assignments= new Assignments();
            $assignments->user_id=$id;
            $assignments->item_name='user';
            $assignments->save();

            $profile=new Profile;
            $profile->user_id= $id;
            $profile->insert();
  }
  
}
