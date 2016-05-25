<?php

namespace app\models;

use Yii;
use app\models\Fileextensions;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $type
 * @property string $avatar
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $uploadedFile;

    public static function tableName()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uploadedFile'], 'file', 'skipOnEmpty' => false],
        //[['uploadedFile'], 'checkextension'],
             [['user_id'], 'integer'],
            [['avatar'], string],
            [['name'], 'string', 'max' => 120],
            [['type'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'type' => 'Type',
            'avatar' => 'Avatar',
        ];
    }
  /*  public function checkextension(){

    }*/

     public function upload()
    {
       $name= $this->uploadedFile->baseName;
       $extension= $this->uploadedFile->extension;

        if(File::findOne(['name'=>$name, 'type'=>$extension])){
           do {
              $name=$name.(File::find()->max('id')+1);
          } while (File::findOne(['name'=>$name, 'type'=>$extension]));
        }

        if (Fileextensions::findOne(['name'=>$this->uploadedFile->extension])) {
            $this->name=$name; 
            $this->user_id=Yii::$app->user->identity->id;
            $this->type=$extension;
            $this->insert();
            $this->uploadedFile->saveAs('../uploads/' . $name . '.' . $extension);
            return true;
       } else {
            return false;
        }
    }
    public function isAuthor(){
       return $this->user_id==Yii::$app->user->identity->id;
    }

}
