<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dialog".
 *
 * @property integer $id
 * @property string $from_id
 * @property string $to_id
 *
 * @property User $to
 * @property User $from
 */
class Dialog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dialog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
          //  [['from_id', 'to_id'], 'required'],
            [['from_id', 'to_id'], 'integer']
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
    public function getLastmessage()
    {
         
        $id= Message::isVisible()->andwhere(['or',['to_id'=>$this->from_id, 'from_id'=>$this->to_id],['from_id'=>$this->from_id, 'to_id'=>$this->to_id]])->max('id');
        return Message::findOne($id);
    }

    public function deleteDialog($id)
    {
         
        $dialog=Dialog::find()->where(['to_id'=>$id])->one();
            $dialog->delete();
        
    }
    public function createDialog($id1, $id2){
      if(!Dialog::find()->where(['to_id'=>$id1, 'from_id'=>$id2])->one()) {
                    $dialog=new Dialog;
                    $dialog->from_id=$id2;
                    $dialog->to_id=$id1;
                    $dialog->insert();
                }
    }
}
