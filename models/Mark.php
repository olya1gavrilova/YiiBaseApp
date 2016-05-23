<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mark".
 *
 * @property integer $user_id
 * @property double $lat
 * @property double $long
 * @property string $status_text
 * @property string $get_date
 */
class Mark extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mark';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'lat', 'long', 'status_text'], 'required'],
            [['user_id'], 'integer'],
            [['lat', 'long'], 'number'],
            [['get_date'], 'safe'],
            [['status_text'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
     public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'lat' => 'Lat',
            'long' => 'Long',
            'status_text' => 'Status Text',
            'get_date' => 'Get Date',
        ];
    }

     public function activeMark(){
        $lastweek=time()-7*24*60*60;
       $date=date(('Y-m-d H:i:s'),$lastweek);

       return $this->get_date > $date ? true : false;
       
     }

     public function findMarks()
    {
       $lastweek=time()-7*24*60*60;
       $date=date(('Y-m-d H:i:s'),$lastweek);
       return Mark::find()->where(['>','get_date', $date ]);
    }
    
    public function activateMark(){
        $this->get_date=date('Y-m-d H:i:s');
        $this->save();
    }
    public function updateMark(){
        $this->long=Yii::$app->request->post('long');
                $this->lat=Yii::$app->request->post('lat');
                $this->status_text=Yii::$app->request->post('marktext');
                $this->save();
    }
   public function createMark(){
            $this->user_id=Yii::$app->user->identity->id;
            $this->long=Yii::$app->request->post('long');
            $this->lat=Yii::$app->request->post('lat');
            $this->status_text=Yii::$app->request->post('marktext');
            $this->insert();
    }
    
}

