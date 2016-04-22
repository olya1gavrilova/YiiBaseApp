<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $text
 * @property string $meta_description
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text', 'meta_description'], 'required'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 48],
            [['url'], 'string', 'max' => 120],
            [['meta_description'], 'string', 'max' => 255],
            [['url'], 'validateUrl']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'text' => 'Text',
            'meta_description' => 'Meta Description',
        ];
    }
    public function translit($text)
    {
    $find=array('А','а','Б','б','В','в','Г','г','Д','д','Е','е','Ё','ё',
        'Ж','ж','З','з','И','и','Й','й','К','к','Л','л','М','м',
        'Н','н','О','о','П','п','Р','р','С','с','Т','т','У','у',
        'Ф','ф','Х','х','Ц','ц','Ч','ч','Ш','ш','Щ','щ','Ъ','ъ',
        'Ы','ы','Ь','ь','Э','э','Ю','ю','Я','я', '№',' ');
 
    $replace=array('A','a','B','b','V','v','G','g','D','d','E','e','Yo','yo',
        'Zh','zh','Z','z','I','i','J','j','K','k','L','l','M','m',
        'N','n','O','o','P','p','R','r','S','s','T','t','U','u','F',
        'f','H','h','Ts','ts','Ch','ch','Sh','sh','Sch','sch',
        '','','Y','y','','','E','e','Yu','yu','Ya','ya', '','-');
 
   return strtolower(preg_replace('/[^\w\d\s_-]*/','',str_replace($find,$replace,$text)));
    }
    public function validateUrl()
    {
        $find=Page::find()->where(['url'=>$this->url])->one();
        if($find){
          return $this->url=$this->url.'-'.(Page::find()->max('id')+1);           
        }
    }
}
