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
            [$features, 'safe'],
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
    public function isAuthor(){
        return Yii::$app->user->identity->id===$this->user_id;
    }
    public function getColumns(){
        return Profile::getTableSchema()->columns;
    }
    public function setCheckbox(){
        foreach ($this->attributes as $key => $value) {

                        $item=Feature::findOne(['name'=>$key]);
                        if(($item->type == 'checkbox' || $item->type == 'multiple') && $value!=''){
                           
                                foreach ($this->$key as $key1 => $value1) {
                                     $nails[]= '^'.$value1.'$';
                                 }
                         $this->$key=implode('\n', $nails);
                        }
                        $nails=[];
                     }
    }
    public function getCheckbox(){
        foreach ($this->attributes as $key => $value) {
                 $item=Feature::findOne(['name'=>$key]);
                        if($item->type == 'checkbox' || $item->type == 'multiple' && $value!=''){
                           $items=explode('\n', $value);
                        
                            foreach ($items as $item) {
                                $items2[]=str_replace(['^','$'],'', $item);
                            }
                            $this->$key=$items2;
                        }
                $items2=[];
        }
    }

   public function findProfile($id){
    $profile=Profile::findOne(['user_id'=>$id]);
        if($profile!==null){
            return $profile;
        }
        else{
            $profile= new Profile;
            $profile->user_id=$id;
            $profile->insert();
            return findProfile();
            }
    }

}
