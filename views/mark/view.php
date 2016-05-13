<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Mark */

$this->title = $model->user->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php if(!$model):?>
            <?= Html::a('Create Mark', ['create'], ['class' => 'btn btn-success btn-lg']) ?>
        <?php else:?>
            <?=$model->status_text?>
        <p>

            <?=  $model->activeMark() ?
             Html::a('Метка активна', 'view', ['class' => 'btn btn-success btn-lg'] ) : 
             Html::a('Активировать', ['activate', 'id' => $model->user_id], ['class' => 'btn btn-default btn-lg']) ?>
            <?= Html::a('Обновить', ['update', 'id' => $model->user_id], ['class' => 'btn btn-primary btn-xs col-sm-offset-2']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->user_id], [
                'class' => 'btn btn-danger btn-xs',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
        
         <?php endif?>
    </p>

        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'user_id',
            'lat',
            'long',
            'status_text',
            // 'get_date',

         //   ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

   <?/*= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            'lat',
            'long',
            'status_text',
            'get_date',
        ],
    ]) */?>

</div>
