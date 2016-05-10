<?php

namespace app\models;

use Yii;
use app\models\Profile;
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
            [['name'], 'unique']
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
    public function addCol($var)
    {   
        switch ($var->type) {
            case  $var->type=='string': $type='string'; break;
            case  $var->type=='text': $type='text(1000)'; break;
            case  $var->type=='select': $type='integer(11)'; break;
            case  $var->type=='radio': $type='integer(11)'; break;
            //case  $var->type=='date': $type='date'; break;
        }
         Yii::$app->db->createCommand()->addColumn('profile' ,$var->name, $type)->execute();
    }
    public function deleteCol($var)
    {
         Yii::$app->db->createCommand()->dropColumn('profile' ,$var->name)->execute();
    }
}
