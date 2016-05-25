<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\models\File */


$this->title = 'Добавить файл';
$this->params['breadcrumbs'][] = ['label' => 'Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        //    ['class' => 'yii\grid\SerialColumn'],

            //'id',
           // 'user_id',
            'name',
            'type',
          //  'avatar',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
