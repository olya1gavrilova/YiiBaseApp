<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $auth_id
 * @property integer $post_id
 * @property string $auth_nick
 * @property string $auth_email
 * @property string $title
 * @property string $text
 * @property string $short_text
 * @property string $status
 * @property string $date
 */
class Comments extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE_GUEST = 'create';
    const SCENARIO_CREATE = 'create_comm';
    const SCENARIO_UPDATE = 'update';
    

    public $captcha;

    public function scenarios()
    {
        return [

            self::SCENARIO_CREATE_GUEST=>['auth_nick', 'title', 'text', 'auth_email','captcha'],
            self::SCENARIO_CREATE => [ 'title', 'text','status'],
            self::SCENARIO_UPDATE => [ 'title', 'text','status'],
        ];
    }

  
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_nick', 'title', 'text', 'auth_email','captcha'], 'required','on' => self::SCENARIO_CREATE_GUEST],
            [['title', 'text'], 'required', 'on' => self::SCENARIO_CREATE],
            [['title', 'text'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['auth_id', 'post_id'], 'integer'],
            [['text','status'], 'string'],
            [['date'], 'safe'],
            ['auth_email', 'email'],
            ['captcha', 'captcha'],
            [['auth_nick'], 'string', 'max' => 20],
            [['auth_email', 'title'], 'string', 'max' => 30],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_id' => 'Auth ID',
            'post_id' => 'Post ID',
            'auth_nick' => 'Auth Nick',
            'auth_email' => 'Auth Email',
            'title' => 'Title',
            'text' => 'Text',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }
     public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
     public function isAuthor($id){
           return $id==Yii::$app->user->id ? true: false;
    }

     public function isPublished($limit, $id){

    $start_publish=time()-($limit*60*60*24);

     $time=Yii::$app->formatter->asDatetime($start_publish, 'php:Y-m-d H:i:s');

      if (Comments::findOne(['id'=>$id])->date > $time){
        return true;
      }
   }
}
