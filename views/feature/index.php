<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Feature;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Свойства профиля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить новое свойство', ['create'], ['class' => 'btn btn-success']) ?>
       <?= Html::a('Все значения свойств', Url::to(['featuretype/index']), ['class' => 'btn btn-info']) ?>
    </p>

   
   

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            
            [
                'attribute' => 'description',
                'value' => function (Feature $data) {
                    if ($data->type=='select' || $data->type=='radio')
                    {
                        return Html::a(Html::encode($data->description), Url::to(['featuretype/create', 'id' => $data->id]));
                    }
                    else{
                        return Html::a(Html::encode($data->description), Url::to(['feature/update', 'id' => $data->id]));
                    }
                    
                },
                'format' => 'raw',
            ],
            'description',
            'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
