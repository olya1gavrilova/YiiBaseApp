<?php

use yii\helpers\Html;

use app\models\Feature;
use yii\grid\GridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Featuretype */

$this->title = 'Значение свойствa '.Feature::findOne(['id'=>$id])->description;
$this->params['breadcrumbs'][] = ['label' => 'Значения свойств', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Список свойств', 'url' => Url::to(['feature/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="featuretype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'feature_id',
            'value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
