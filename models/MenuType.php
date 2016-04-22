<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_type".
 *
 * @property integer $id
 * @property string $menu_type
 */
class MenuType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_type'], 'required'],
            [['menu_type'], 'string', 'max' => 24]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_type' => 'Menu Type',
        ];
    }
}
