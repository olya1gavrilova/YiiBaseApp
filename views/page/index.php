<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Page;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pages', ['create'], ['class' => 'btn btn-success']) ?>
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
                    return Html::a(Html::encode($data->url), \yii\helpers\Url::to(['page/view', 'id' => $data->url]));
                },
                'format' => 'raw',
            ],
           // 'text:ntext',
            'meta_description',
            
            ['class' => 'yii\grid\ActionColumn',
            'urlCreator'=>function($action, $model, $key, $index){
                     return \yii\helpers\Url::to(['page/'.$action.'/'.$model->url]);
             }
         
            ],
        ],
    ]); ?>

</div>
