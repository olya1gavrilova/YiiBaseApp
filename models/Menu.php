<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $menu_id
 * @property string $menu_item
 * @property string $menu_url
 * @property integer $type
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item', 'menu_url', 'type'], 'required'],
            [['type'], 'integer'],
            [['menu_item'], 'string', 'max' => 20],
            [['menu_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'menu_item' => 'Menu Item',
            'menu_url' => 'Menu Url',
            'type' => 'Type',
        ];
    }

    public function getMenuType()
    {
        return $this->hasOne(MenuType::className(), ['id' => 'type']);
    }
}
