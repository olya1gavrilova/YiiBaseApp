<?php

namespace app\models;

use Yii;
use app\models\Profile;
use app\models\Featuretype;
use yii\db\Command;
/**
 * This is the model class for table "features".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $type
 *
 * @property Profile[] $profiles
 */
class Feature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'features';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['type'], 'string'],
            [['name', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['feature_id' => 'id']);
    }
    public function addCol()
    {   
        
        switch ($this->type) {
            case  $this->type=='string': $type='string'; break;
            case  $this->type=='text': $type='text(1000)'; break;
            case  $this->type=='select': $type='integer(11)'; break;
            case  $this->type=='radio': $type='integer(11)'; break;
            case  $this->type=='checkbox': $type='string'; break;
            case  $this->type=='multiple': $type='string'; break;
           
        }
         Yii::$app->db->createCommand()->addColumn('profile' ,$this->name, $type)->execute();
    }
    public function deleteCol()
    {
         Yii::$app->db->createCommand()->dropColumn('profile' ,$this->name)->execute();
         Featuretype::deleteAll(['feature_id'=>$this->id]);

    }
    public function renameCol($oldvar)
    {
        if($oldvar!=$this->name){
             return  Yii::$app->db->createCommand()->renameColumn('profile' ,$oldvar,$this->name)->execute();       
                }
        else{
            return false;
        }
        
    }
}
