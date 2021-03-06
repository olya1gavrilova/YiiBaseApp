<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_post".
 *
 * @property string $id
 * @property string $title
 * @property string $anons
 * @property string $content
 * @property string $category_id
 * @property string $author_id
 * @property string $publish_status
 * @property string $publish_date
 *
 * @property User $author
 * @property TblCategory $category
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','content','end_publish','publish_date'], 'required'],
            [['anons', 'content', 'publish_status'], 'string'],
            [['category_id', 'author_id'], 'integer'],
            [['publish_date','end_publish'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'anons' => 'Anons',
            'content' => 'Content',
            'category_id' => 'Category ID',
            'author_id' => 'Author ID',
            'publish_status' => 'Publish Status',
            'publish_date' => 'Publish Date',
            'end_publish' => 'End Publish'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    
    /*public function isAuthor($id){
           return Post::find()->where(['id'=>$id])->one()->author_id===Yii::$app->user->id ;
    }*/
    public function isAuthor(){
           return $this->author_id==Yii::$app->user->id ;
    }

    //время публикации в сутках
    
    public function isPublished(){


      return Post::find()->where(['<', 'publish_date', date('Y-m-d H:i:s')])->andWhere(['>', 'end_publish', date('Y-m-d H:i:s')])->andWhere(['publish_status'=>'publish']);
    }


     public function hasAnons(){

        if($this->anons==''){
            return $this->anons=StringHelper::truncateWords(strip_tags($model->content, 50));
        }
      
    }
}