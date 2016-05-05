<?php

namespace app\models;

use Yii;

use app\models\User;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $from_id
 * @property string $to_id
 * @property string $date
 * @property string $text
 * @property string $viewed
 * @property string $deleted_by_sender
 * @property string $deleted_by_reciever
 *
 * @property User $to
 * @property User $from
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required', 'message'=>'Введите текст сообщения'],
            [['from_id', 'to_id'], 'integer'],
            [['date'], 'safe'],
            [['text', 'viewed', 'deleted_by'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_id' => 'From ID',
            'to_id' => 'To ID',
            'date' => 'Date',
            'text' => 'Текст сообщения',
            'viewed' => 'Viewed',
            'deleted_by' => 'Deleted By', 
            //столбец deleted_by принимает значение 0, если сообщение доступно обоим пользователям
            // значение id пользователя, который нажал "удалить сообщение" и значение both, если собщение удалено
            //обоими пользователями

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTo()
    {
        return $this->hasOne(User::className(), ['id' => 'to_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'from_id']);
    }
    public function isVisible()
    {
        return  Message::find()->where(['and',['!=','deleted_by', Yii::$app->user->identity->id],['!=','deleted_by', 'both']]);
    }
    public function deleteMessage($model){
        if($model->deleted_by=='0' || $model->deleted_by== Yii::$app->user->identity->id){
            //условие $model->deleted_by== Yii::$app->user->identity->id сделано для того, чтобы из-за какого-нибудь сбоя 
            //пользователь не мог 2 раза запустить delete и выставить значение both
            $model->deleted_by=Yii::$app->user->identity->id;
        }
        else{
            $model->deleted_by='both';
        }
        $model->save();
    }
}
