<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Page;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
      <?php if(Yii::$app->user->can('page-control')):?>
            <?= Html::a('Create Pages', ['create'], ['class' => 'btn btn-success']) ?>
      <?php endif?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'url',
            [
                'attribute' => 'url',
                'value' => function (Page $data) {
                    return Html::a(Html::encode($data->url), Url::to(['page/view', 'id' => $data->url]));
                },
                'format' => 'raw',
            ],
           // 'text:ntext',
            'meta_description',
            [
                'attribute'=>'status',
                'visible'=> Yii::$app->user->can('page-control')
            ],
            
            ['class' => 'yii\grid\ActionColumn',
            'urlCreator'=>function($action, $model, $key, $index){
                     return Url::to(['page/'.$action.'/'.$model->url]);
                },
            'visible'=> Yii::$app->user->can('page-control')
         
            ],
        ],
    ]); ?>

</div>
