<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\models\Category;
use app\models\User;
use app\models\Post;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1>Все <?= Html::encode($this->title) ?> <?=$user?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
  
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        
        'columns' => [

            'id',
            'title',
            [
                'attribute' => 'Anons',
                'value' => function (Post $data) {
                    if($data->anons!=''){
                      return Html::a(Html::encode($data->anons), \yii\helpers\Url::to(['post/view', 'id' => $data->id]));
                    }
                    else{
                        return Html::a(Html::encode(StringHelper::truncateWords($data->content, 25)), \yii\helpers\Url::to(['post/view', 'id' => $data->id]));
                    }
                },
                'format' => 'raw',
            ],
            //'content:ntext',
            'category.title',
             //'author_id',
            'publish_status',
            // 'publish_date',

            ['class' => 'yii\grid\ActionColumn',
            'visible' =>  Yii::$app->user->can('update-post') || User::isAuthor($id) &&  !Yii::$app->user->isGuest,
            ],
        ],
    ]); ?>

</div>
