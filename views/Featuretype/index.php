<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\Featuretype;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Значения свойств';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuretype-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
       
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            [
                'attribute' => 'Feature',
                'value' => function (Featuretype $data) {
                    return Html::a(Html::encode($data->feature->description), Url::to(['featuretype/create', 'id' => $data->feature_id]));
                },
                'format' => 'raw',
            ],
            'value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
