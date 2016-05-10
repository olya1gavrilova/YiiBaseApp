<?php

namespace app\models;

use Yii;

use app\models\Feature;

/**
 * This is the model class for table "profile".
 *
 * @property string $user_id
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        foreach (Profile::getColumns() as $key) {
           $features[]=$key->name;
        }

        return [
          //  [['user_id'], 'required'],
           // [['user_id'], 'integer'],
            [$features, 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function isAuthor($id){
        if(Yii::$app->user->identity->id===$id){
            return true;
        }
    }
    public function getColumns(){
        return Profile::getTableSchema()->columns;
    }
}
