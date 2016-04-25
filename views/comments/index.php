<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\widgets\LinkPager;
use app\models\Comments;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?= Html::encode($this->title) ?></h1>

     

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
            [
                'attribute' => 'Comment',
                'value' => function (Comments $data) {
                    return Html::a(Html::encode($data->title), \yii\helpers\Url::to(['post/view', 'id' => $data->post_id]));
                },
                'format' => 'raw',
            ],
           'post.title',
            'auth_nick',
            //'auth_email:email',
          //  'title',
            // 'text:ntext',
            // 'short_text',
            'status',
            // 'date',

            ['class' => 'yii\grid\ActionColumn',

             'urlCreator'=>function($action, $model, $key, $index){
                if($action==='view'){
                    return \yii\helpers\Url::to(['post/'.$action.'/'.$model->post_id]);
                }
                else {
                    return \yii\helpers\Url::to(['comments/'.$action.'/'.$model->id]);
                }
            },
            'visible' =>  Yii::$app->user->can('comment-list'),
            ],
        ],
    ]); ?>

     

</div>
